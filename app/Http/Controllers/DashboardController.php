<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\HealthRecord;
use App\Models\BehaviorRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'health_today' => HealthRecord::whereDate('date', today())->count(),
            'violations_today' => BehaviorRecord::whereDate('date', today())->count(),
            'total_violations' => BehaviorRecord::count(),
        ];

        $recent_violations = BehaviorRecord::with(['student', 'violationType', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $recent_health = HealthRecord::with(['student', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_violations', 'recent_health'));
    }
}

