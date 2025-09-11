<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,teacher');
    }

    /**
     * Enrollment Dashboard - Overview of all enrollments
     */
    public function dashboard(Request $request)
    {
        $query = Enrollment::with(['student.user', 'class', 'section'])
                          ->orderBy('enrolled_at', 'desc');

        // Filters
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->paginate(20);
        $classes = SchoolClass::active()->get();
        $sections = Section::active()->get();
        
        // Statistics
        $stats = [
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::current()->count(),
            'enrolled_count' => Enrollment::byStatus('enrolled')->count(),
            'promoted_count' => Enrollment::byStatus('promoted')->count(),
            'graduated_count' => Enrollment::byStatus('graduated')->count(),
            'withdrawn_count' => Enrollment::byStatus('withdrawn')->count(),
        ];

        return view('enrollments.dashboard', compact('enrollments', 'classes', 'sections', 'stats'));
    }

    /**
     * List enrollments for a student
     */
    public function index($studentId)
    {
        $student = Student::with(['user', 'class', 'enrollments.class', 'enrollments.section'])
            ->findOrFail($studentId);

        $classes = SchoolClass::active()->get();
        $sections = Section::active()->get();

        return view('enrollments.index', compact('student', 'classes', 'sections'));
    }

    /**
     * Enroll or update enrollment for a student
     */
    public function store(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);

        $data = $request->all();
        $validator = Validator::make($data, [
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'academic_year' => 'required|string',
            'enrolled_at' => 'nullable|date',
            'status' => 'required|in:enrolled,promoted,transferred,graduated,withdrawn',
            'notes' => 'nullable|string',
            'withdrawal_reason' => 'nullable|string|required_if:status,withdrawn',
            'withdrawal_date' => 'nullable|date|required_if:status,withdrawn',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Deactivate current enrollment if enrolling in new class
            if ($data['status'] === 'enrolled') {
                Enrollment::where('student_id', $student->id)
                         ->where('is_active', true)
                         ->update(['is_active' => false]);
            }

            $enrollment = Enrollment::create([
                'student_id' => $student->id,
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'] ?? null,
                'academic_year' => $data['academic_year'],
                'enrolled_at' => $data['enrolled_at'] ?? now(),
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'withdrawal_reason' => $data['withdrawal_reason'] ?? null,
                'withdrawal_date' => $data['withdrawal_date'] ?? null,
                'is_active' => in_array($data['status'], ['enrolled', 'promoted']),
            ]);

            DB::commit();

            return redirect()->route('enrollments.index', $student->id)
                           ->with('success', 'Enrollment saved successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Enrollment creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create enrollment: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Bulk enrollment operations
     */
    public function bulkEnroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_ids' => 'required|array|min:1',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'academic_year' => 'required|string',
            'status' => 'required|in:enrolled,promoted',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $enrollments = [];
            foreach ($request->student_ids as $studentId) {
                // Deactivate current enrollment
                Enrollment::where('student_id', $studentId)
                         ->where('is_active', true)
                         ->update(['is_active' => false]);

                $enrollments[] = [
                    'student_id' => $studentId,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'academic_year' => $request->academic_year,
                    'enrolled_at' => now(),
                    'status' => $request->status,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Enrollment::insert($enrollments);

            DB::commit();

            return back()->with('success', 'Bulk enrollment completed successfully. ' . count($enrollments) . ' students enrolled.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk enrollment failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Bulk enrollment failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Promote all students in a class
     */
    public function promoteClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_class_id' => 'required|exists:classes,id',
            'to_class_id' => 'required|exists:classes,id',
            'academic_year' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $students = Student::where('class_id', $request->from_class_id)
                             ->where('is_active', true)
                             ->get();

            $enrollments = [];
            foreach ($students as $student) {
                // Deactivate current enrollment
                Enrollment::where('student_id', $student->id)
                         ->where('is_active', true)
                         ->update(['is_active' => false]);

                $enrollments[] = [
                    'student_id' => $student->id,
                    'class_id' => $request->to_class_id,
                    'academic_year' => $request->academic_year,
                    'enrolled_at' => now(),
                    'status' => 'promoted',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Enrollment::insert($enrollments);

            DB::commit();

            return back()->with('success', 'Class promotion completed. ' . count($enrollments) . ' students promoted.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Class promotion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Class promotion failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Enrollment Reports and Analytics
     */
    public function reports(Request $request)
    {
        $query = Enrollment::with(['student.user', 'class', 'section']);

        // Apply filters
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_range')) {
            $dateRange = $request->date_range;
            switch ($dateRange) {
                case 'this_month':
                    $query->whereMonth('enrolled_at', now()->month);
                    break;
                case 'last_month':
                    $query->whereMonth('enrolled_at', now()->subMonth()->month);
                    break;
                case 'this_year':
                    $query->whereYear('enrolled_at', now()->year);
                    break;
                case 'last_year':
                    $query->whereYear('enrolled_at', now()->subYear()->year);
                    break;
            }
        }

        $enrollments = $query->get();

        // Calculate statistics
        $stats = [
            'total_enrollments' => $enrollments->count(),
            'active_enrollments' => $enrollments->where('is_active', true)->count(),
            'graduation_rate' => $this->calculateGraduationRate($enrollments),
            'retention_rate' => $this->calculateRetentionRate($enrollments),
            'by_status' => $enrollments->groupBy('status')->map->count()->toArray(),
            'by_class' => $enrollments->groupBy('class.name')->map->count()->toArray(),
            'by_academic_year' => $enrollments->groupBy('academic_year')->map->count()->toArray(),
        ];

        // Class-wise detailed statistics
        $classStats = $this->getClassStatistics($enrollments);

        return view('enrollments.reports', compact('stats', 'classStats'));
    }

    /**
     * Get enrollment statistics
     */
    public function statistics()
    {
        $stats = [
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::current()->count(),
            'by_status' => Enrollment::selectRaw('status, COUNT(*) as count')
                                   ->groupBy('status')
                                   ->pluck('count', 'status'),
            'by_class' => Enrollment::with('class')
                                   ->selectRaw('class_id, COUNT(*) as count')
                                   ->groupBy('class_id')
                                   ->get()
                                   ->mapWithKeys(function($item) {
                                       return [$item->class->name => $item->count];
                                   }),
            'by_academic_year' => Enrollment::selectRaw('academic_year, COUNT(*) as count')
                                           ->groupBy('academic_year')
                                           ->orderBy('academic_year', 'desc')
                                           ->pluck('count', 'academic_year'),
        ];

        return response()->json($stats);
    }

    /**
     * Calculate graduation rate
     */
    private function calculateGraduationRate($enrollments)
    {
        $total = $enrollments->count();
        if ($total === 0) return 0;
        
        $graduated = $enrollments->where('status', 'graduated')->count();
        return ($graduated / $total) * 100;
    }

    /**
     * Calculate retention rate
     */
    private function calculateRetentionRate($enrollments)
    {
        $total = $enrollments->count();
        if ($total === 0) return 0;
        
        $retained = $enrollments->whereIn('status', ['enrolled', 'promoted'])->count();
        return ($retained / $total) * 100;
    }

    /**
     * Get class-wise statistics
     */
    private function getClassStatistics($enrollments)
    {
        $classStats = [];
        
        foreach ($enrollments->groupBy('class.name') as $className => $classEnrollments) {
            $enrolled = $classEnrollments->where('status', 'enrolled')->count();
            $promoted = $classEnrollments->where('status', 'promoted')->count();
            $graduated = $classEnrollments->where('status', 'graduated')->count();
            $withdrawn = $classEnrollments->where('status', 'withdrawn')->count();
            $total = $classEnrollments->count();
            
            $retentionRate = $total > 0 ? (($enrolled + $promoted) / $total) * 100 : 0;
            
            $classStats[] = [
                'class_name' => $className,
                'enrolled' => $enrolled,
                'promoted' => $promoted,
                'graduated' => $graduated,
                'withdrawn' => $withdrawn,
                'total' => $total,
                'retention_rate' => $retentionRate,
            ];
        }
        
        return $classStats;
    }
}
