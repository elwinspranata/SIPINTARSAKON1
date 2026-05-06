<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\ViolationType;
use App\Models\VitaminType;
use App\Models\BehaviorRecord;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function recap(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $search = $request->get('search');
        $classId = $request->get('class_id');

        $query = Student::with([
            'schoolClass',
            'behaviorRecords' => function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->where('date', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('date', '<=', $endDate);
                }
                $query->with(['violationType', 'vitaminType']);
            }
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $classes = SchoolClass::where('is_active', true)->orderBy('tingkat')->orderBy('name')->get();
        $students = (clone $query)->orderBy('name')->paginate(15)->withQueryString();
        $summaryStudents = (clone $query)->get();

        $totalStudents = $summaryStudents->count();
        $avgScore = $summaryStudents->count() ? $summaryStudents->avg('net_points') : 0;
        $criticalCount = $summaryStudents->filter(fn($s) => $s->point_status['label'] === 'KRITIS')->count();

        return view('records.recap', compact('students', 'classes', 'startDate', 'endDate', 'search', 'classId', 'totalStudents', 'avgScore', 'criticalCount'));
    }

    public function index(Request $request)
    {
        $violation_records = BehaviorRecord::with(['student', 'user', 'violationType'])
            ->whereNotNull('violation_type_id')
            ->latest()
            ->paginate(10, ['*'], 'violation_page');

        $vitamin_records = BehaviorRecord::with(['student', 'user', 'vitaminType'])
            ->whereNotNull('vitamin_type_id')
            ->latest()
            ->paginate(10, ['*'], 'vitamin_page');

        return view('records.index', compact('violation_records', 'vitamin_records'));
    }

    public function create()
    {
        $type = request('type', 'violation');
        $classes = SchoolClass::where('is_active', true)->orderBy('tingkat')->orderBy('name')->get();
        $violation_types = ViolationType::all()->groupBy('category');
        $vitamin_types = VitaminType::all()->groupBy('category');

        return view('records.create', compact('classes', 'violation_types', 'vitamin_types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:violation,achievement',
            'student_id' => 'required|exists:students,id',
        ]);

        if ($request->type === 'achievement') {
            $request->validate([
                'vitamin_type_id' => 'required|exists:vitamin_types,id',
            ]);

            BehaviorRecord::create([
                'student_id' => $request->student_id,
                'user_id' => auth()->id(),
                'vitamin_type_id' => $request->vitamin_type_id,
                'date' => now()->toDateString(),
                'notes' => $request->notes,
            ]);
        } else {
            $request->validate([
                'violation_type_id' => 'required|exists:violation_types,id',
            ]);

            BehaviorRecord::create([
                'student_id' => $request->student_id,
                'user_id' => auth()->id(),
                'violation_type_id' => $request->violation_type_id,
                'date' => now()->toDateString(),
                'notes' => $request->notes,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Catatan berhasil disimpan!');
    }

    public function edit(BehaviorRecord $record)
    {
        $record->load(['student', 'violationType', 'vitaminType']);
        $violation_types = ViolationType::all()->groupBy('category');
        $vitamin_types = VitaminType::all()->groupBy('category');

        return view('records.edit', compact('record', 'violation_types', 'vitamin_types'));
    }

    public function update(Request $request, BehaviorRecord $record)
    {
        if ($record->vitamin_type_id) {
            $request->validate([
                'vitamin_type_id' => 'required|exists:vitamin_types,id',
            ]);
            $record->update([
                'vitamin_type_id' => $request->vitamin_type_id,
                'notes' => $request->notes,
            ]);
        } else {
            $request->validate([
                'violation_type_id' => 'required|exists:violation_types,id',
            ]);
            $record->update([
                'violation_type_id' => $request->violation_type_id,
                'notes' => $request->notes,
            ]);
        }

        return redirect()->route('records.index')->with('success', 'Catatan berhasil diperbarui!');
    }

    public function destroy(BehaviorRecord $record)
    {
        $record->delete();
        return redirect()->route('records.index')->with('success', 'Catatan berhasil dihapus!');
    }
}
