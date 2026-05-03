<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\BehaviorRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_violations' => BehaviorRecord::whereNotNull('violation_type_id')->count(),
            'total_vitamins' => BehaviorRecord::whereNotNull('vitamin_type_id')->count(),
            'violations_today' => BehaviorRecord::whereNotNull('violation_type_id')->whereDate('date', today())->count(),
            'vitamins_today' => BehaviorRecord::whereNotNull('vitamin_type_id')->whereDate('date', today())->count(),
        ];

        $recent_violations = BehaviorRecord::with(['student', 'violationType', 'user'])
            ->whereNotNull('violation_type_id')
            ->latest()
            ->take(5)
            ->get();

        $recent_vitamins = BehaviorRecord::with(['student', 'vitaminType', 'user'])
            ->whereNotNull('vitamin_type_id')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_violations', 'recent_vitamins'));
    }
}
