<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Fee::with(['student.user', 'student.schoolClass']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('fee_type')) {
            $query->where('fee_type', $request->fee_type);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('school_class_id', $request->class_id);
            });
        }

        $fees = $query->latest()->paginate(15);
        $classes = SchoolClass::where('is_active', true)->get();

        // Calculate statistics
        $stats = [
            'total_fees' => Fee::sum('amount'),
            'paid_fees' => Fee::where('status', 'paid')->sum('amount'),
            'pending_fees' => Fee::where('status', 'pending')->sum('amount'),
            'overdue_fees' => Fee::where('status', 'overdue')->sum('amount'),
        ];

        return view('admin.fees.index', compact('fees', 'classes', 'stats'));
    }

    public function create()
    {
        $students = Student::with(['user', 'schoolClass'])->where('is_active', true)->get();
        $classes = SchoolClass::where('is_active', true)->get();
        
        return view('admin.fees.create', compact('students', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_type' => 'required|in:tuition,admission,examination,library,transport,hostel,laboratory,sports,other',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'academic_year' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Fee::create($request->all());

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee record created successfully.');
    }

    public function show(Fee $fee)
    {
        $fee->load(['student.user', 'student.schoolClass']);
        return view('admin.fees.show', compact('fee'));
    }

    public function edit(Fee $fee)
    {
        $students = Student::with(['user', 'schoolClass'])->where('is_active', true)->get();
        return view('admin.fees.edit', compact('fee', 'students'));
    }

    public function update(Request $request, Fee $fee)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_type' => 'required|in:tuition,admission,examination,library,transport,hostel,laboratory,sports,other',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'academic_year' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'paid_date' => 'nullable|date',
            'payment_method' => 'nullable|in:cash,bank_transfer,cheque,online,card',
        ]);

        $fee->update($request->all());

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee record updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee record deleted successfully.');
    }

    public function markAsPaid(Fee $fee)
    {
        $fee->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Fee marked as paid successfully.');
    }

    public function bulkCreate()
    {
        $classes = SchoolClass::where('is_active', true)->get();
        return view('admin.fees.bulk-create', compact('classes'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'fee_type' => 'required|in:tuition,admission,examination,library,transport,hostel,laboratory,sports,other',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'academic_year' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $students = Student::where('school_class_id', $request->class_id)
            ->where('is_active', true)
            ->get();

        foreach ($students as $student) {
            Fee::create([
                'student_id' => $student->id,
                'fee_type' => $request->fee_type,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'academic_year' => $request->academic_year,
                'description' => $request->description,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('admin.fees.index')
            ->with('success', "Fee records created for {$students->count()} students.");
    }

    public function revenue()
    {
        // Monthly revenue data
        $monthlyRevenue = Fee::where('status', 'paid')
            ->selectRaw('YEAR(paid_date) as year, MONTH(paid_date) as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Fee type breakdown
        $feeTypeRevenue = Fee::where('status', 'paid')
            ->selectRaw('fee_type, SUM(amount) as total')
            ->groupBy('fee_type')
            ->get();

        // Class-wise revenue
        $classRevenue = Fee::where('status', 'paid')
            ->join('students', 'fees.student_id', '=', 'students.id')
            ->join('school_classes', 'students.school_class_id', '=', 'school_classes.id')
            ->selectRaw('school_classes.name as class_name, SUM(fees.amount) as total')
            ->groupBy('school_classes.id', 'school_classes.name')
            ->get();

        // Teacher salary expenses
        $teacherSalaries = Teacher::where('is_active', true)->sum('salary');
        $monthlyTeacherExpense = $teacherSalaries; // Assuming monthly salaries

        // Calculate profit/loss
        $totalRevenue = Fee::where('status', 'paid')->sum('amount');
        $totalExpenses = $monthlyTeacherExpense * 12; // Annual teacher expenses
        $netProfit = $totalRevenue - $totalExpenses;

        // Outstanding fees
        $outstandingFees = Fee::where('status', 'pending')
            ->orWhere('status', 'overdue')
            ->sum('amount');

        // Recent payments
        $recentPayments = Fee::where('status', 'paid')
            ->with(['student.user', 'student.schoolClass'])
            ->latest('paid_date')
            ->limit(10)
            ->get();

        return view('admin.fees.revenue', compact(
            'monthlyRevenue',
            'feeTypeRevenue', 
            'classRevenue',
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'outstandingFees',
            'recentPayments',
            'teacherSalaries'
        ));
    }

    public function updateOverdue()
    {
        $overdueFees = Fee::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        return redirect()->back()
            ->with('success', "{$overdueFees} fees marked as overdue.");
    }
}
