<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['subject.schoolClass', 'teacher.user'])->paginate(15);
        return view('admin.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $subjects = Subject::with('schoolClass')->where('is_active', true)->get();
        return view('admin.assignments.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date|after:today',
            'total_marks' => 'required|integer|min:1',
            'instructions' => 'nullable|string',
        ]);

        $subject = Subject::find($request->subject_id);
        
        Assignment::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'teacher_id' => auth()->user()->isTeacher() ? auth()->user()->teacher->id : $subject->teachers->first()->id,
            'school_class_id' => $subject->school_class_id,
            'due_date' => $request->due_date,
            'total_marks' => $request->total_marks,
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['subject.schoolClass', 'teacher.user']);
        return view('admin.assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment)
    {
        $subjects = Subject::with('schoolClass')->where('is_active', true)->get();
        return view('admin.assignments.edit', compact('assignment', 'subjects'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date',
            'total_marks' => 'required|integer|min:1',
            'instructions' => 'nullable|string',
        ]);

        $assignment->update($request->all());

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }
}