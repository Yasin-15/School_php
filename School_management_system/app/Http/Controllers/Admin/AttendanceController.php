<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['student.user', 'student.schoolClass']);

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('school_class_id', $request->class_id);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $attendances = $query->paginate(15);
        $classes = SchoolClass::where('is_active', true)->get();

        return view('admin.attendance.index', compact('attendances', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $sections = Section::where('is_active', true)->get();
        return view('admin.attendance.create', compact('classes', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $request->date,
                ],
                [
                    'status' => $status,
                    'marked_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance marked successfully.');
    }

    public function getStudents(Request $request)
    {
        $query = Student::with('user')->where('school_class_id', $request->class_id);

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $students = $query->where('is_active', true)->get();
        $date = $request->date ?? Carbon::today()->format('Y-m-d');

        // Get existing attendance for the date
        $existingAttendance = Attendance::where('date', $date)
            ->whereIn('student_id', $students->pluck('id'))
            ->pluck('status', 'student_id');

        return response()->json([
            'students' => $students,
            'existing_attendance' => $existingAttendance,
        ]);
    }

    public function report(Request $request)
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $students = collect();
        $attendanceData = collect();

        if ($request->filled(['class_id', 'start_date', 'end_date'])) {
            $students = Student::with('user')
                ->where('school_class_id', $request->class_id)
                ->where('is_active', true)
                ->get();

            $attendanceData = Attendance::whereBetween('date', [$request->start_date, $request->end_date])
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->groupBy('student_id');
        }

        return view('admin.attendance.report', compact('classes', 'students', 'attendanceData'));
    }
}