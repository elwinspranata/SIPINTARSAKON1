<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['schoolClass', 'behaviorRecords.violationType', 'behaviorRecords.vitaminType']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->orderBy('name')->paginate(15)->withQueryString();
        $classes = SchoolClass::where('is_active', true)->orderBy('tingkat')->orderBy('name')->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('tingkat')->orderBy('name')->get();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20|unique:students,nisn',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:L,P',
        ]);

        Student::create($request->only(['name', 'nisn', 'class_id', 'gender']));

        return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function show(Student $student)
    {
        $student->load(['schoolClass', 'behaviorRecords.violationType', 'behaviorRecords.vitaminType', 'behaviorRecords.user']);
        $classes = SchoolClass::where('is_active', true)->orderBy('tingkat')->orderBy('name')->get();
        return view('students.show', compact('student', 'classes'));
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('tingkat')->orderBy('name')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20|unique:students,nisn,' . $student->id,
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:L,P',
        ]);

        $student->update($request->only(['name', 'nisn', 'class_id', 'gender']));

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus!');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:students,id',
        ]);

        Student::whereIn('id', $request->ids)->delete();

        return redirect()->route('students.index')->with('success', count($request->ids) . ' siswa berhasil dihapus!');
    }

    /**
     * Get student points (JSON for AJAX).
     */
    public function getPoints(Student $student)
    {
        $student->load(['behaviorRecords.violationType', 'behaviorRecords.vitaminType']);
        $vPoints = $student->behaviorRecords->whereNotNull('violation_type_id')->sum(fn($r) => $r->violationType->points ?? 0);
        $aPoints = $student->behaviorRecords->whereNotNull('vitamin_type_id')->sum(fn($r) => $r->vitaminType->points ?? 0);
        $points = max(0, $vPoints - $aPoints);

        if ($points > 100) { $status = 'KRITIS'; $color = 'danger'; }
        elseif ($points > 50) { $status = 'WASPADA'; $color = 'warning'; }
        elseif ($points > 20) { $status = 'BAIK'; $color = 'info'; }
        else { $status = 'AMAN'; $color = 'success'; }

        return response()->json([
            'name' => $student->name,
            'points' => $points,
            'violation_points' => $vPoints,
            'vitamin_points' => $aPoints,
            'status' => $status,
            'color' => $color,
        ]);
    }

    /**
     * Show printable letter for student.
     */
    public function letter(Student $student)
    {
        $student->load(['schoolClass', 'behaviorRecords.violationType', 'behaviorRecords.vitaminType', 'behaviorRecords.user']);

        $violationRecords = $student->behaviorRecords->whereNotNull('violation_type_id')->sortByDesc('date');
        $vitaminRecords   = $student->behaviorRecords->whereNotNull('vitamin_type_id')->sortByDesc('date');

        $totalViolation = $violationRecords->sum(fn($r) => $r->violationType->points ?? 0);
        $totalVitamin   = $vitaminRecords->sum(fn($r) => $r->vitaminType->points ?? 0);
        $netPoints      = $totalViolation - $totalVitamin;  // bisa negatif jika vitamin > penyakit
        $netDisplay     = max(0, $netPoints);               // tampilan poin bersih tidak boleh negatif

        if ($netDisplay > 100)     { $statusText = 'ZONA MERAH — KRITIS';   $statusSP = 'SP III'; }
        elseif ($netDisplay > 50)  { $statusText = 'ZONA KUNING — WASPADA'; $statusSP = 'SP II'; }
        elseif ($netDisplay > 20)  { $statusText = 'ZONA BIRU — PERHATIAN'; $statusSP = 'SP I'; }
        else                       { $statusText = 'ZONA HIJAU — AMAN';      $statusSP = 'Normal'; }

        // Generate nomor surat: SP/SIPINTAR/[ID]/[BULAN-ROMAWI]/[TAHUN]
        $bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        $nomorSurat  = sprintf(
            '%03d/SP-SIPINTAR/%s/%s',
            $student->id,
            $bulanRomawi[now()->month - 1],
            now()->year
        );

        return view('students.letter', compact(
            'student', 'violationRecords', 'vitaminRecords',
            'totalViolation', 'totalVitamin', 'netPoints', 'netDisplay',
            'statusText', 'statusSP', 'nomorSurat'
        ));
    }
}
