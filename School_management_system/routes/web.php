<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboard;
use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isTeacher()) {
            return redirect('/teacher/dashboard');
        } elseif ($user->isStudent()) {
            return redirect('/student/dashboard');
        } elseif ($user->isParent()) {
            return redirect('/parent/dashboard');
        }
        
        return view('dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Student management
        Route::resource('students', StudentController::class);
        
        // Teacher management
        Route::resource('teachers', TeacherController::class);
        
        // Class management
        Route::resource('classes', ClassController::class);
        
        // Subject management
        Route::resource('subjects', SubjectController::class);
        
        // Fee management
        Route::resource('fees', FeeController::class);
        Route::post('fees/{fee}/mark-paid', [FeeController::class, 'markAsPaid'])->name('fees.mark-paid');
        Route::get('fees-bulk-create', [FeeController::class, 'bulkCreate'])->name('fees.bulk-create');
        Route::post('fees-bulk-store', [FeeController::class, 'bulkStore'])->name('fees.bulk-store');
        Route::get('fees-revenue', [FeeController::class, 'revenue'])->name('fees.revenue');
        Route::post('fees-update-overdue', [FeeController::class, 'updateOverdue'])->name('fees.update-overdue');
        
        // Attendance management
        Route::resource('attendance', AttendanceController::class)->except(['show', 'edit', 'update']);
        Route::get('attendance/students', [AttendanceController::class, 'getStudents'])->name('attendance.students');
        Route::get('attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
        
        // Exam management
        Route::resource('exams', ExamController::class);
        
        // Grade management
        Route::resource('grades', GradeController::class);
        Route::get('grades/students-by-exam', [GradeController::class, 'getStudentsByExam'])->name('grades.students-by-exam');
        
        // Timetable management
        Route::resource('timetables', TimetableController::class);
        Route::get('timetables-view', [TimetableController::class, 'viewByClass'])->name('timetables.view-by-class');
        
        // Notice management
        Route::resource('notices', NoticeController::class);
        Route::patch('notices/{notice}/toggle-status', [NoticeController::class, 'toggleStatus'])->name('notices.toggle-status');
        
        // Assignment management
        Route::resource('assignments', AssignmentController::class);
    });

    // Teacher routes
    Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    });

    // Student routes
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    });

    // Parent routes
    Route::middleware(['role:parent'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [ParentDashboard::class, 'index'])->name('dashboard');
    });
});
