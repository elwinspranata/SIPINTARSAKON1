<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\ViolationType;
use App\Models\HealthRecord;
use App\Models\BehaviorRecord;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        $behavior_records = BehaviorRecord::with(['student', 'user', 'violationType'])
            ->latest()
            ->paginate(15, ['*'], 'behavior_page');

        $health_records = HealthRecord::with(['student', 'user'])
            ->latest()
            ->paginate(15, ['*'], 'health_page');
        
        return view('records.index', compact('health_records', 'behavior_records'));
    }

    public function create()
    {
        $classes = SchoolClass::with('students')->get();
        $violation_types = ViolationType::all()->groupBy('category');
        
        return view('records.create', compact('classes', 'violation_types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:health,behavior,violation,achievement',
            'student_id' => 'required|exists:students,id',
        ]);

        if ($request->type === 'health') {
            $request->validate([
                'date' => 'required|date',
            ]);

            HealthRecord::create([
                'student_id' => $request->student_id,
                'user_id' => auth()->id(),
                'date' => $request->date,
                'temperature' => $request->temperature,
                'blood_pressure' => $request->blood_pressure,
                'health_problem' => $request->health_problem,
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
        $record->load(['student', 'violationType']);
        $classes = SchoolClass::with('students')->get();
        $violation_types = ViolationType::all()->groupBy('category');

        return view('records.edit', compact('record', 'classes', 'violation_types'));
    }

    public function update(Request $request, BehaviorRecord $record)
    {
        $request->validate([
            'violation_type_id' => 'required|exists:violation_types,id',
        ]);

        $record->update([
            'violation_type_id' => $request->violation_type_id,
            'notes' => $request->notes,
        ]);

        return redirect()->route('records.index')->with('success', 'Catatan berhasil diperbarui!');
    }

    public function destroy(BehaviorRecord $record)
    {
        $record->delete();
        return redirect()->route('records.index')->with('success', 'Catatan berhasil dihapus!');
    }
}
