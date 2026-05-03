<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount('students')->orderBy('tingkat')->orderBy('name')->get()->groupBy('tingkat');
        $totalClasses = SchoolClass::count();
        $totalStudents = Student::count();

        return view('classes.index', compact('classes', 'totalClasses', 'totalStudents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tingkat' => 'required|in:X,XI,XII',
            'name' => 'required|string|max:255|unique:classes,name',
        ]);

        SchoolClass::create($request->only(['tingkat', 'name']));

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function update(Request $request, SchoolClass $schoolClass)
    {
        $request->validate([
            'tingkat' => 'required|in:X,XI,XII',
            'name' => 'required|string|max:255|unique:classes,name,' . $schoolClass->id,
        ]);

        $schoolClass->update($request->only(['tingkat', 'name']));

        return redirect()->route('classes.index')
            ->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy(SchoolClass $schoolClass)
    {
        $studentCount = Student::where('class_id', $schoolClass->id)->count();

        if ($studentCount > 0) {
            return redirect()->route('classes.index')
                ->with('error', "Tidak dapat menghapus! Terdapat {$studentCount} siswa di kelas ini. Pindahkan atau hapus siswa terlebih dahulu.");
        }

        $schoolClass->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }

    /**
     * Get students of a class (JSON for AJAX).
     */
    public function getStudents(SchoolClass $schoolClass)
    {
        $students = $schoolClass->students()->orderBy('name')->get(['id', 'name', 'nisn']);
        return response()->json($students);
    }

    /**
     * Bulk transfer students to another class.
     */
    public function bulkTransfer(Request $request)
    {
        $request->validate([
            'source_class_id' => 'required|exists:classes,id',
            'target_class_id' => 'required|exists:classes,id|different:source_class_id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $count = Student::whereIn('id', $request->student_ids)
            ->where('class_id', $request->source_class_id)
            ->update(['class_id' => $request->target_class_id]);

        $targetClass = SchoolClass::find($request->target_class_id);

        return redirect()->route('classes.index')
            ->with('success', "{$count} siswa berhasil dipindahkan ke kelas {$targetClass->name}!");
    }
}
