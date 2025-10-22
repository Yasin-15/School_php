<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'schoolClass', 'section'])
            ->paginate(15);

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $sections = Section::where('is_active', true)->get();
        $studentRole = Role::where('name', 'student')->first();

        return view('admin.students.create', compact('classes', 'sections', 'studentRole'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'student_id' => 'required|string|unique:students',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'admission_date' => 'required|date',
            'roll_number' => 'nullable|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email',
            'emergency_contact' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:5',
            'medical_conditions' => 'nullable|string',
            'transport_required' => 'boolean',
        ]);

        DB::transaction(function () use ($request) {
            $studentRole = Role::where('name', 'student')->first();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $studentRole->id,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'school_class_id' => $request->school_class_id,
                'section_id' => $request->section_id,
                'admission_date' => $request->admission_date,
                'roll_number' => $request->roll_number,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'emergency_contact' => $request->emergency_contact,
                'blood_group' => $request->blood_group,
                'medical_conditions' => $request->medical_conditions,
                'transport_required' => $request->boolean('transport_required'),
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'schoolClass', 'section', 'fees', 'grades.exam', 'attendances']);
        
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $sections = Section::where('is_active', true)->get();
        
        return view('admin.students.edit', compact('student', 'classes', 'sections'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'admission_date' => 'required|date',
            'roll_number' => 'nullable|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email',
            'emergency_contact' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:5',
            'medical_conditions' => 'nullable|string',
            'transport_required' => 'boolean',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'is_active' => $request->boolean('is_active'),
            ]);

            $student->update([
                'student_id' => $request->student_id,
                'school_class_id' => $request->school_class_id,
                'section_id' => $request->section_id,
                'admission_date' => $request->admission_date,
                'roll_number' => $request->roll_number,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'emergency_contact' => $request->emergency_contact,
                'blood_group' => $request->blood_group,
                'medical_conditions' => $request->medical_conditions,
                'transport_required' => $request->boolean('transport_required'),
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $student->user->delete();
            $student->delete();
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}