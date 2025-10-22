<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Exam;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Grade::with(['student.user', 'exam.subject']);

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('school_class_id', $request->class_id);
            });
        }

        $grades = $query->paginate(15);
        $exams = Exam::with('subject')->get();
        $classes = SchoolClass::where('is_active', true)->get();

        return view('admin.grades.index', compact('grades', 'exams', 'classes'));
    }

    public function create()
    {
        $exams = Exam::with(['subject', 'schoolClass'])->get();
        return view('admin.grades.create', compact('exams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.marks_obtained' => 'required|numeric|min:0',
        ]);

        foreach ($request->grades as $gradeData) {
            $exam = Exam::find($request->exam_id);
            
            if ($gradeData['marks_obtained'] > $exam->total_marks) {
                return back()->withErrors([
                    'grades' => 'Marks obtained cannot exceed total marks for the exam.'
                ]);
            }

            Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'exam_id' => $request->exam_id,
                ],
                [
                    'marks_obtained' => $gradeData['marks_obtained'],
                    'grade' => $this->calculateGrade($gradeData['marks_obtained'], $exam->total_marks),
                    'remarks' => $gradeData['remarks'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grades saved successfully.');
    }

    public function getStudentsByExam(Request $request)
    {
        $exam = Exam::with('schoolClass')->find($request->exam_id);
        
        if (!$exam) {
            return response()->json(['error' => 'Exam not found'], 404);
        }

        $students = Student::with('user')
            ->where('school_class_id', $exam->school_class_id)
            ->where('is_active', true)
            ->get();

        // Get existing grades
        $existingGrades = Grade::where('exam_id', $request->exam_id)
            ->whereIn('student_id', $students->pluck('id'))
            ->pluck('marks_obtained', 'student_id');

        return response()->json([
            'students' => $students,
            'exam' => $exam,
            'existing_grades' => $existingGrades,
        ]);
    }

    public function show(Grade $grade)
    {
        $grade->load(['student.user', 'exam.subject']);
        return view('admin.grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $exams = Exam::with(['subject', 'schoolClass'])->get();
        return view('admin.grades.edit', compact('grade', 'exams'));
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'marks_obtained' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $exam = $grade->exam;
        
        if ($request->marks_obtained > $exam->total_marks) {
            return back()->withErrors([
                'marks_obtained' => 'Marks obtained cannot exceed total marks for the exam.'
            ]);
        }

        $grade->update([
            'marks_obtained' => $request->marks_obtained,
            'grade' => $this->calculateGrade($request->marks_obtained, $exam->total_marks),
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade deleted successfully.');
    }

    private function calculateGrade($marksObtained, $totalMarks)
    {
        $percentage = ($marksObtained / $totalMarks) * 100;

        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 33) return 'D';
        return 'F';
    }
}