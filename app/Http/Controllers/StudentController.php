<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\BehaviorRecord;
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

    public function resetPoints(Request $request)
    {
        $request->validate([
            'ids' => 'sometimes|array',
            'ids.*' => 'exists:students,id',
            'all' => 'sometimes|boolean',
        ]);

        if ($request->filled('all')) {
            $studentIds = Student::pluck('id')->toArray();
            BehaviorRecord::whereIn('student_id', $studentIds)->delete();
            return redirect()->route('students.index')->with('success', 'Semua poin siswa berhasil direset.');
        }

        if (!$request->filled('ids')) {
            return redirect()->route('students.index')->with('error', 'Pilih siswa terlebih dahulu untuk mereset poin.');
        }

        $studentIds = $request->ids;
        BehaviorRecord::whereIn('student_id', $studentIds)->delete();

        return redirect()->route('students.index')->with('success', count($studentIds) . ' siswa berhasil direset poinnya.');
    }

    /**
     * Get student points (JSON for AJAX).
     */
    public function getPoints(Student $student)
    {
        $student->load(['behaviorRecords.violationType', 'behaviorRecords.vitaminType']);
        $status = $student->point_status;
        $colorMap = ['SEHAT' => 'primary', 'AMAN' => 'success', 'BAIK' => 'info', 'WASPADA' => 'warning', 'KRITIS' => 'danger'];

        return response()->json([
            'name' => $student->name,
            'nisn' => $student->nisn,
            'points' => $student->net_points,
            'violation_points' => $student->violation_points,
            'vitamin_points' => $student->vitamin_points,
            'status' => $status['label'],
            'color' => $colorMap[$status['label']] ?? 'success',
        ]);
    }

    /**
     * Show printable letter for student.
     */
    public function letter(Student $student)
    {
        $student->load(['schoolClass', 'behaviorRecords.violationType', 'behaviorRecords.vitaminType', 'behaviorRecords.user']);

        $violationRecords = $student->behaviorRecords->whereNotNull('violation_type_id')->sortByDesc('date');
        $vitaminRecords = $student->behaviorRecords->whereNotNull('vitamin_type_id')->sortByDesc('date');

        $totalViolation = $student->violation_points;
        $totalVitamin = $student->vitamin_points;
        $netPoints = $student->net_points;
        $status = $student->point_status;
        $statusText = $status['zone'];
        $statusSP = $status['sp'];

        // Generate nomor surat: SP/SIPINTAR/[ID]/[BULAN-ROMAWI]/[TAHUN]
        $bulanRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $nomorSurat = sprintf(
            '%03d/SP-SIPINTAR/%s/%s',
            $student->id,
            $bulanRomawi[now()->month - 1],
            now()->year
        );

        // Data Grafik: 6 Bulan Terakhir
        $chartLabels = [];
        $chartVitaminData = [];
        $chartViolationData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartLabels[] = $date->translatedFormat('M');

            $chartVitaminData[] = $vitaminRecords
                ->filter(fn($r) => \Carbon\Carbon::parse($r->date)->format('Y-m') === $date->format('Y-m'))
                ->sum(fn($r) => $r->vitaminType->points ?? 0);

            $chartViolationData[] = $violationRecords
                ->filter(fn($r) => \Carbon\Carbon::parse($r->date)->format('Y-m') === $date->format('Y-m'))
                ->sum(fn($r) => $r->violationType->points ?? 0);
        }

        return view('students.letter', compact(
            'student',
            'violationRecords',
            'vitaminRecords',
            'totalViolation',
            'totalVitamin',
            'netPoints',
            'statusText',
            'statusSP',
            'nomorSurat',
            'chartLabels',
            'chartVitaminData',
            'chartViolationData'
        ));
    }
}
