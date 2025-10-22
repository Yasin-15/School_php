<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Fee;
use App\Models\Attendance;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::where('is_active', true)->count(),
            'total_teachers' => Teacher::where('is_active', true)->count(),
            'total_classes' => SchoolClass::where('is_active', true)->count(),
            'pending_fees' => Fee::where('status', 'pending')->sum('amount'),
            'overdue_fees' => Fee::where('status', 'overdue')->sum('amount'),
            'today_attendance' => Attendance::whereDate('date', today())->count(),
        ];

        $recent_students = Student::with(['user', 'schoolClass'])
            ->latest()
            ->take(5)
            ->get();

        $recent_fees = Fee::with(['student.user'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_students', 'recent_fees'));
    }
}