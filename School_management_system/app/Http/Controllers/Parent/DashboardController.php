<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Notice;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Fee;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get children (students) associated with this parent
        $children = Student::whereHas('parents', function ($query) {
                $query->where('parent_id', auth()->id());
            })
            ->with(['user', 'schoolClass', 'section'])
            ->get();

        // If no children found, try to find by parent email/phone match
        if ($children->isEmpty()) {
            $children = Student::where('parent_email', auth()->user()->email)
                ->orWhere('parent_phone', auth()->user()->phone)
                ->with(['user', 'schoolClass', 'section'])
                ->get();
        }

        $childrenData = [];
        
        foreach ($children as $child) {
            // Get recent attendance
            $recentAttendance = Attendance::where('student_id', $child->id)
                ->latest()
                ->limit(5)
                ->get();

            // Get recent grades
            $recentGrades = Grade::with(['exam.subject'])
                ->where('student_id', $child->id)
                ->latest()
                ->limit(5)
                ->get();

            // Get pending fees
            $pendingFees = Fee::where('student_id', $child->id)
                ->where('status', 'pending')
                ->sum('amount');

            // Get attendance stats for current month
            $currentMonth = Carbon::now()->format('Y-m');
            $attendanceStats = Attendance::where('student_id', $child->id)
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->selectRaw('
                    COUNT(*) as total_days,
                    SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days,
                    SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_days,
                    SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days
                ')
                ->first();

            $childrenData[] = [
                'student' => $child,
                'recent_attendance' => $recentAttendance,
                'recent_grades' => $recentGrades,
                'pending_fees' => $pendingFees,
                'attendance_stats' => $attendanceStats,
            ];
        }

        // Get notices for parents
        $notices = Notice::where(function ($query) use ($children) {
                $query->where('target_audience', 'all')
                    ->orWhere('target_audience', 'parents');
                
                // Include class-specific notices for children's classes
                if ($children->isNotEmpty()) {
                    $classIds = $children->pluck('school_class_id')->unique();
                    $query->orWhere(function ($q) use ($classIds) {
                        $q->where('target_audience', 'specific_class')
                          ->whereIn('school_class_id', $classIds);
                    });
                }
            })
            ->where('publish_date', '<=', Carbon::now())
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', Carbon::now());
            })
            ->where('is_active', true)
            ->latest()
            ->limit(5)
            ->get();

        return view('parent.dashboard', compact('childrenData', 'notices'));
    }
}