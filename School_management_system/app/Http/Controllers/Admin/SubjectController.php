<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['schoolClass', 'teachers'])->paginate(15);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        return view('admin.subjects.create', compact('classes', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:subjects',
            'school_class_id' => 'required|exists:school_classes,id',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $subject = Subject::create($request->all());

        if ($request->has('teacher_ids')) {
            $subject->teachers()->attach($request->teacher_ids);
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['schoolClass', 'teachers.user', 'timetables']);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        $subject->load('teachers');
        return view('admin.subjects.edit', compact('subject', 'classes', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:subjects,code,' . $subject->id,
            'school_class_id' => 'required|exists:school_classes,id',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $subject->update($request->all());

        if ($request->has('teacher_ids')) {
            $subject->teachers()->sync($request->teacher_ids);
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->teachers()->detach();
        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}