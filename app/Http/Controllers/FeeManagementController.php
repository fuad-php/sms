<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\FeeStructure;
use App\Models\FeePayment;
use App\Models\FeeInstallment;
use App\Models\SchoolClass;
use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeeManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher');
    }

    /**
     * Fee Management Dashboard
     */
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_fee_structures' => FeeStructure::active()->count(),
            'total_payments_today' => FeePayment::whereDate('payment_date', today())->sum('total_amount'),
            'total_payments_month' => FeePayment::whereMonth('payment_date', now()->month)
                                               ->whereYear('payment_date', now()->year)
                                               ->sum('total_amount'),
            'pending_installments' => FeeInstallment::pending()->count(),
            'overdue_installments' => FeeInstallment::overdue()->count(),
        ];

        $recent_payments = FeePayment::with(['student.user', 'feeStructure'])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();

        $overdue_installments = FeeInstallment::with(['student.user', 'feeStructure'])
                                             ->overdue()
                                             ->orderBy('due_date')
                                             ->limit(10)
                                             ->get();

        return view('fees.dashboard', compact('stats', 'recent_payments', 'overdue_installments'));
    }

    /**
     * Student Fee Management
     */
    public function studentFees(Request $request)
    {
        $query = Student::with(['user', 'class']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(20);
        $classes = SchoolClass::orderBy('name')->get();

        return view('fees.students.index', compact('students', 'classes'));
    }

    /**
     * Student Fee Details
     */
    public function studentFeeDetails(Student $student)
    {
        $student->load(['user', 'class']);
        
        // Get fee structures for student's class
        $feeStructures = FeeStructure::forClass($student->class_id)
                                    ->active()
                                    ->with(['feeCategory'])
                                    ->get();

        // Get student's payments
        $payments = FeePayment::forStudent($student->id)
                             ->with(['feeStructure.feeCategory', 'installment'])
                             ->orderBy('payment_date', 'desc')
                             ->get();

        // Get student's installments
        $installments = FeeInstallment::forStudent($student->id)
                                     ->with(['feeStructure.feeCategory'])
                                     ->orderBy('due_date')
                                     ->get();

        // Calculate fee summary
        $feeSummary = $this->calculateFeeSummary($student, $feeStructures, $payments);

        return view('fees.students.details', compact(
            'student', 
            'feeStructures', 
            'payments', 
            'installments', 
            'feeSummary'
        ));
    }

    /**
     * Fee Collection
     */
    public function collectFee(Request $request)
    {
        try {
            // If no student_id is provided, show the student selection form
            if (!$request->has('student_id') || !$request->student_id) {
                $students = Student::with(['user', 'class'])
                                  ->orderBy('class_id')
                                  ->get()
                                  ->sortBy(function($student) {
                                      return $student->user->name;
                                  });
                
                return view('fees.collect', compact('students'));
            }
            
            $student = Student::with(['user', 'class'])->findOrFail($request->student_id);
            
            $feeStructures = FeeStructure::forClass($student->class_id)
                                        ->active()
                                        ->with(['feeCategory'])
                                        ->get();

            $installments = FeeInstallment::forStudent($student->id)
                                         ->pending()
                                         ->with(['feeStructure.feeCategory'])
                                         ->orderBy('due_date')
                                         ->get();

            return view('fees.collect', compact('student', 'feeStructures', 'installments'));
        } catch (\Exception $e) {
            \Log::error('Fee collection error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Process Fee Payment
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'installment_id' => 'nullable|exists:fee_installments,id',
            'amount' => 'required|numeric|min:0.01',
            'discount_amount' => 'nullable|numeric|min:0',
            'late_fee' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online,card',
            'transaction_id' => 'nullable|string',
            'cheque_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Generate payment reference
            $paymentReference = 'PAY-' . strtoupper(uniqid());

            // Calculate total amount
            $totalAmount = $request->amount + $request->late_fee - $request->discount_amount;

            // Create payment record
            $payment = FeePayment::create([
                'student_id' => $request->student_id,
                'fee_structure_id' => $request->fee_structure_id,
                'installment_id' => $request->installment_id,
                'payment_reference' => $paymentReference,
                'amount' => $request->amount,
                'discount_amount' => $request->discount_amount ?? 0,
                'late_fee' => $request->late_fee ?? 0,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'cheque_number' => $request->cheque_number,
                'bank_name' => $request->bank_name,
                'payment_date' => $request->payment_date,
                'status' => 'completed',
                'notes' => $request->notes,
                'received_by' => auth()->id(),
            ]);

            // Update installment status if applicable
            if ($request->installment_id) {
                $installment = FeeInstallment::find($request->installment_id);
                $installment->update([
                    'status' => 'paid',
                    'paid_date' => $request->payment_date,
                ]);
            }

            DB::commit();

            return redirect()->route('fees.student-details', $request->student_id)
                            ->with('success', 'Payment processed successfully. Reference: ' . $paymentReference);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Fee Reports
     */
    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $classId = $request->get('class_id');

        $query = FeePayment::with(['student.user', 'feeStructure.feeCategory'])
                          ->forDateRange($startDate, $endDate);

        if ($classId) {
            $query->whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $payments = $query->orderBy('payment_date', 'desc')->get();

        $summary = [
            'total_amount' => $payments->sum('total_amount'),
            'total_payments' => $payments->count(),
            'by_method' => $payments->groupBy('payment_method')->map->sum('total_amount'),
            'by_class' => $payments->groupBy('student.class.name')->map->sum('total_amount'),
        ];

        $classes = SchoolClass::orderBy('name')->get();

        return view('fees.reports', compact('payments', 'summary', 'classes', 'startDate', 'endDate'));
    }

    /**
     * Generate Fee Installments
     */
    public function generateInstallments(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'academic_year' => 'required|string',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $students = $class->students;
        $feeStructures = FeeStructure::forClass($class->id)->active()->get();

        DB::beginTransaction();

        try {
            foreach ($students as $student) {
                foreach ($feeStructures as $feeStructure) {
                    if ($feeStructure->installments > 1) {
                        $installmentAmount = $feeStructure->amount / $feeStructure->installments;
                        
                        for ($i = 1; $i <= $feeStructure->installments; $i++) {
                            $dueDate = $this->calculateDueDate($feeStructure, $i);
                            
                            FeeInstallment::create([
                                'student_id' => $student->id,
                                'fee_structure_id' => $feeStructure->id,
                                'installment_number' => $i,
                                'amount' => $installmentAmount,
                                'due_date' => $dueDate,
                                'status' => 'pending',
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return back()->with('success', 'Fee installments generated successfully for ' . $class->name);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to generate installments: ' . $e->getMessage());
        }
    }

    /**
     * Calculate fee summary for student
     */
    private function calculateFeeSummary($student, $feeStructures, $payments)
    {
        $totalFees = $feeStructures->sum('amount');
        $totalPaid = $payments->where('status', 'completed')->sum('total_amount');
        $totalPending = $totalFees - $totalPaid;

        return [
            'total_fees' => $totalFees,
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'payment_percentage' => $totalFees > 0 ? round(($totalPaid / $totalFees) * 100, 2) : 0,
        ];
    }

    /**
     * Calculate due date for installment
     */
    private function calculateDueDate($feeStructure, $installmentNumber)
    {
        $baseDate = $feeStructure->due_date ?? now();
        
        switch ($feeStructure->frequency) {
            case 'monthly':
                return $baseDate->copy()->addMonths($installmentNumber - 1);
            case 'quarterly':
                return $baseDate->copy()->addMonths(($installmentNumber - 1) * 3);
            case 'half_yearly':
                return $baseDate->copy()->addMonths(($installmentNumber - 1) * 6);
            case 'yearly':
                return $baseDate->copy()->addYears($installmentNumber - 1);
            default:
                return $baseDate;
        }
    }
}
