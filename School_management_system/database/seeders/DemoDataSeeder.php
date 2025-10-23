<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Fee;
use App\Models\Notice;
use App\Models\Assignment;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $studentRole = Role::where('name', 'student')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        
        // Create more students
        $classes = SchoolClass::with('sections')->get();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        foreach ($classes->take(3) as $class) {
            foreach ($class->sections->take(2) as $section) {
                // Create 5 students per section
                for ($i = 1; $i <= 5; $i++) {
                    $studentUser = User::create([
                        'name' => "Student {$class->grade_level}{$section->name}{$i}",
                        'email' => "student{$class->grade_level}{$section->name}{$i}" . time() . "@school.com",
                        'password' => Hash::make('password'),
                        'role_id' => $studentRole->id,
                        'phone' => '+123456789' . $class->grade_level . $i,
                        'date_of_birth' => Carbon::now()->subYears(5 + $class->grade_level)->subDays(rand(1, 365)),
                        'gender' => $i % 2 == 0 ? 'female' : 'male',
                        'is_active' => true,
                    ]);

                    $student = Student::create([
                        'user_id' => $studentUser->id,
                        'student_id' => "STU{$class->grade_level}{$section->name}{$i}" . rand(100, 999),
                        'school_class_id' => $class->id,
                        'section_id' => $section->id,
                        'admission_date' => Carbon::now()->subMonths(rand(1, 12)),
                        'roll_number' => str_pad($i, 3, '0', STR_PAD_LEFT),
                        'parent_name' => "Parent of {$studentUser->name}",
                        'parent_phone' => '+987654321' . $class->grade_level . $i,
                        'parent_email' => "parent{$class->grade_level}{$section->name}{$i}" . time() . "@school.com",
                        'is_active' => true,
                    ]);

                    // Create attendance records for the last 30 days
                    for ($day = 0; $day < 30; $day++) {
                        $date = Carbon::now()->subDays($day);
                        if ($date->isWeekday()) {
                            Attendance::create([
                                'student_id' => $student->id,
                                'date' => $date,
                                'status' => rand(1, 10) > 2 ? 'present' : (rand(1, 2) == 1 ? 'absent' : 'late'),
                                'marked_by' => 1, // Admin user
                            ]);
                        }
                    }

                    // Create multiple fee records per student
                    $feeTypes = ['tuition', 'admission', 'library', 'transport', 'sports'];
                    $feeAmounts = [
                        'tuition' => rand(800, 1200),
                        'admission' => rand(200, 400),
                        'library' => rand(20, 50),
                        'transport' => rand(100, 200),
                        'sports' => rand(30, 80),
                    ];

                    foreach ($feeTypes as $feeType) {
                        $isPaid = rand(1, 4) != 1; // 75% chance of being paid
                        Fee::create([
                            'student_id' => $student->id,
                            'fee_type' => $feeType,
                            'amount' => $feeAmounts[$feeType],
                            'due_date' => Carbon::now()->addDays(rand(-30, 60)),
                            'status' => $isPaid ? 'paid' : (rand(1, 2) == 1 ? 'pending' : 'overdue'),
                            'paid_date' => $isPaid ? Carbon::now()->subDays(rand(1, 90)) : null,
                            'academic_year' => '2024-2025',
                        ]);
                    }
                }
            }
        }

        // Create more teachers
        for ($i = 2; $i <= 5; $i++) {
            $teacherUser = User::create([
                'name' => "Teacher {$i}",
                'email' => "teacher{$i}" . time() . "@school.com",
                'password' => Hash::make('password'),
                'role_id' => $teacherRole->id,
                'phone' => '+1234567890' . $i,
                'date_of_birth' => Carbon::now()->subYears(rand(25, 50)),
                'gender' => $i % 2 == 0 ? 'female' : 'male',
                'is_active' => true,
            ]);

            Teacher::create([
                'user_id' => $teacherUser->id,
                'employee_id' => "TCH" . str_pad($i + rand(200, 999), 3, '0', STR_PAD_LEFT),
                'joining_date' => Carbon::now()->subYears(rand(1, 10)),
                'qualification' => ['Bachelor of Education', 'Master of Science', 'PhD in Mathematics'][rand(0, 2)],
                'experience_years' => rand(1, 15),
                'salary' => rand(40000, 80000),
                'is_active' => true,
            ]);
        }

        // Assign teachers to subjects (only if not already assigned)
        $teachers = Teacher::all();
        foreach ($subjects as $subject) {
            if ($subject->teachers()->count() == 0) {
                $randomTeachers = $teachers->random(rand(1, min(2, $teachers->count())));
                $subject->teachers()->attach($randomTeachers->pluck('id'));
            }
        }

        // Create exams
        foreach ($subjects->take(10) as $subject) {
            $exam = Exam::create([
                'name' => 'Mid Term Exam - ' . $subject->name,
                'school_class_id' => $subject->school_class_id,
                'subject_id' => $subject->id,
                'exam_date' => Carbon::now()->addDays(rand(10, 60)),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'total_marks' => 100,
                'passing_marks' => 40,
                'academic_year' => '2024-2025',
                'exam_type' => 'mid_term',
            ]);

            // Create grades for students in this class
            $classStudents = Student::where('school_class_id', $subject->school_class_id)->get();
            foreach ($classStudents as $student) {
                $marks = rand(30, 95);
                Grade::create([
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'marks_obtained' => $marks,
                    'grade_letter' => $this->calculateGrade($marks),
                    'grade_points' => $this->calculateGradePoints($marks),
                ]);
            }
        }

        // Create assignments
        foreach ($subjects->take(8) as $subject) {
            $teacher = $subject->teachers->first();
            if ($teacher) {
                Assignment::create([
                    'title' => 'Assignment - ' . $subject->name,
                    'description' => 'Complete the exercises from chapter 1-3 and submit your solutions.',
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'school_class_id' => $subject->school_class_id,
                    'due_date' => Carbon::now()->addDays(rand(7, 21)),
                    'total_marks' => rand(20, 50),
                    'instructions' => 'Please write your answers clearly and show all working.',
                ]);
            }
        }

        // Create notices
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $audiences = ['all', 'students', 'teachers', 'parents'];
        
        for ($i = 1; $i <= 10; $i++) {
            Notice::create([
                'title' => "School Notice {$i}",
                'content' => "This is the content of school notice {$i}. It contains important information for the school community.",
                'priority' => $priorities[array_rand($priorities)],
                'target_audience' => $audiences[array_rand($audiences)],
                'publish_date' => Carbon::now()->subDays(rand(0, 30)),
                'expiry_date' => Carbon::now()->addDays(rand(30, 90)),
                'is_urgent' => rand(1, 5) == 1,
                'is_published' => true,
                'created_by' => 1, // Admin user
            ]);
        }

        // Create timetables
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $times = [
            ['09:00:00', '10:00:00'],
            ['10:00:00', '11:00:00'],
            ['11:30:00', '12:30:00'],
            ['13:30:00', '14:30:00'],
            ['14:30:00', '15:30:00'],
        ];

        foreach ($classes->take(3) as $class) {
            $classSubjects = Subject::where('school_class_id', $class->id)->get();
            
            foreach ($days as $dayIndex => $day) {
                foreach ($times as $timeIndex => $time) {
                    if ($classSubjects->count() > 0) {
                        $subject = $classSubjects->random();
                        $teacher = $subject->teachers->first();
                        
                        if ($teacher) {
                            Timetable::create([
                                'school_class_id' => $class->id,
                                'subject_id' => $subject->id,
                                'teacher_id' => $teacher->id,
                                'day_of_week' => $day,
                                'start_time' => $time[0],
                                'end_time' => $time[1],
                                'room_number' => 'Room ' . rand(101, 205),
                                'academic_year' => '2024-2025',
                                'is_active' => true,
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function calculateGrade($marks)
    {
        if ($marks >= 90) return 'A+';
        if ($marks >= 80) return 'A';
        if ($marks >= 70) return 'B+';
        if ($marks >= 60) return 'B';
        if ($marks >= 50) return 'C+';
        if ($marks >= 40) return 'C';
        if ($marks >= 33) return 'D';
        return 'F';
    }

    private function calculateGradePoints($marks)
    {
        if ($marks >= 90) return 4.0;
        if ($marks >= 80) return 3.7;
        if ($marks >= 70) return 3.3;
        if ($marks >= 60) return 3.0;
        if ($marks >= 50) return 2.7;
        if ($marks >= 40) return 2.0;
        if ($marks >= 33) return 1.0;
        return 0.0;
    }
}