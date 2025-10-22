<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Timetable;
use App\Models\Exam;
use App\Models\Notice;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Grade;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', auth()->id())->first();
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student profile not found.');
        }

        // Get today's timetable
        $todayTimetable = Timetable::with(['subject', 'teacher.user'])
            ->where('school_class_id', $student->school_class_id)
            ->where('day_of_week', strtolower(Carbon::now()->format('l')))
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        // Get upcoming exams
        $upcomingExams = Exam::with('subject')
            ->where('school_class_id', $student->school_class_id)
            ->where('exam_date', '>=', Carbon::now())
            ->orderBy('exam_date')
            ->limit(5)
            ->get();

        // Get recent notices
        $notices = Notice::where(function ($query) use ($student) {
                $query->where('target_audience', 'all')
                    ->orWhere('target_audience', 'students')
                    ->orWhere(function ($q) use ($student) {
                        $q->where('target_audience', 'specific_class')
                          ->where('school_class_id', $student->school_class_id);
                    });
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

        // Get pending assignments
        $pendingAssignments = Assignment::whereHas('subject', function ($query) use ($student) {
                $query->where('school_class_id', $student->school_class_id);
            })
            ->where('due_date', '>=', Carbon::now())
            ->where('is_active', true)
            ->limit(5)
            ->get();

        // Get attendance summary for current month
        $currentMonth = Carbon::now()->format('Y-m');
        $attendanceStats = Attendance::where('student_id', $student->id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_days,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days
            ')
            ->first();

        // Get recent grades
        $recentGrades = Grade::with(['exam.subject'])
            ->where('student_id', $student->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('student.dashboard', compact(
            'student',
            'todayTimetable',
            'upcomingExams',
            'notices',
            'pendingAssignments',
            'attendanceStats',
            'recentGrades'
        ));
    }
}