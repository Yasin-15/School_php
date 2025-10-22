<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $query = Timetable::with(['schoolClass', 'subject', 'teacher.user']);

        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $timetables = $query->orderBy('day_of_week')->orderBy('start_time')->paginate(15);
        $classes = SchoolClass::where('is_active', true)->get();

        return view('admin.timetables.index', compact('timetables', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        
        return view('admin.timetables.create', compact('classes', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'academic_year' => 'required|string',
        ]);

        // Check for conflicts
        $conflict = Timetable::where('school_class_id', $request->school_class_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('academic_year', $request->academic_year)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['time' => 'Time slot conflicts with existing timetable.']);
        }

        Timetable::create($request->all());

        return redirect()->route('admin.timetables.index')
            ->with('success', 'Timetable created successfully.');
    }

    public function show(Timetable $timetable)
    {
        $timetable->load(['schoolClass', 'subject', 'teacher.user']);
        return view('admin.timetables.show', compact('timetable'));
    }

    public function edit(Timetable $timetable)
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        
        return view('admin.timetables.edit', compact('timetable', 'classes', 'subjects', 'teachers'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'academic_year' => 'required|string',
        ]);

        // Check for conflicts (excluding current timetable)
        $conflict = Timetable::where('school_class_id', $request->school_class_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('academic_year', $request->academic_year)
            ->where('id', '!=', $timetable->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['time' => 'Time slot conflicts with existing timetable.']);
        }

        $timetable->update($request->all());

        return redirect()->route('admin.timetables.index')
            ->with('success', 'Timetable updated successfully.');
    }

    public function destroy(Timetable $timetable)
    {
        $timetable->delete();

        return redirect()->route('admin.timetables.index')
            ->with('success', 'Timetable deleted successfully.');
    }

    public function viewByClass(Request $request)
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $timetable = collect();

        if ($request->filled('class_id')) {
            $timetable = Timetable::with(['subject', 'teacher.user'])
                ->where('school_class_id', $request->class_id)
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day_of_week');
        }

        return view('admin.timetables.view-by-class', compact('classes', 'timetable'));
    }
}