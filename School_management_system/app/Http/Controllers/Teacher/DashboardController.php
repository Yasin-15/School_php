<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\Exam;
use App\Models\Notice;
use App\Models\Assignment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        
        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Teacher profile not found.');
        }

        // Get today's classes
        $todayClasses = Timetable::with(['schoolClass', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->where('day_of_week', strtolower(Carbon::now()->format('l')))
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        // Get upcoming exams
        $upcomingExams = Exam::with(['schoolClass', 'subject'])
            ->whereHas('subject.teachers', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->where('exam_date', '>=', Carbon::now())
            ->orderBy('exam_date')
            ->limit(5)
            ->get();

        // Get recent notices
        $notices = Notice::where(function ($query) {
                $query->where('target_audience', 'all')
                    ->orWhere('target_audience', 'teachers');
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

        // Get pending assignments to grade
        $pendingAssignments = Assignment::whereHas('subject.teachers', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->where('due_date', '>=', Carbon::now())
            ->where('is_active', true)
            ->count();

        // Get classes taught by teacher
        $myClasses = $teacher->subjects()
            ->with('schoolClass')
            ->get()
            ->pluck('schoolClass')
            ->unique('id');

        return view('teacher.dashboard', compact(
            'teacher',
            'todayClasses',
            'upcomingExams',
            'notices',
            'pendingAssignments',
            'myClasses'
        ));
    }
}