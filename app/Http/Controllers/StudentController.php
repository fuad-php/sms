<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:admin,teacher')->except(['show', 'profile']);
        $this->middleware('role:admin,teacher,student,parent')->only(['show', 'profile']);
    }

    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $query = Student::with(['user', 'class'])
            ->active();

        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('student_id', 'like', "%{$search}%")
              ->orWhere('roll_number', 'like', "%{$search}%");
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $students = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            
            'student_id' => 'required|string|unique:students,student_id',
            'class_id' => 'required|exists:classes,id',
            'admission_date' => 'required|date',
            'roll_number' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'medical_info' => 'nullable|string',
            'guardian_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'guardian_email' => 'nullable|email',
            'guardian_address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
            ]);

            // Create student record
            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'admission_date' => $request->admission_date,
                'roll_number' => $request->roll_number,
                'blood_group' => $request->blood_group,
                'medical_info' => $request->medical_info,
                'guardian_name' => $request->guardian_name,
                'guardian_phone' => $request->guardian_phone,
                'guardian_email' => $request->guardian_email,
                'guardian_address' => $request->guardian_address,
                'emergency_contact' => $request->emergency_contact,
            ]);

            DB::commit();

            $student->load('user', 'class');

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'data' => $student
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified student
     */
    public function show($id)
    {
        $user = auth()->user();
        
        $student = Student::with(['user', 'class', 'attendances.subject', 'examResults.exam.subject'])
            ->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        // Access control - parents can only see their children
        if ($user->role === 'parent') {
            $parent = $user->parent;
            if (!$parent->students->contains($student)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }
        }

        // Students can only see their own profile
        if ($user->role === 'student' && $user->student->id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Add additional statistics
        $student->attendance_percentage = $student->getAttendancePercentage();
        $student->grade_average = $student->getCurrentGradeAverage();

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|between:2,100',
            'email' => 'sometimes|string|email|max:100|unique:users,email,' . $student->user_id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            
            'student_id' => 'sometimes|string|unique:students,student_id,' . $id,
            'class_id' => 'sometimes|exists:classes,id',
            'roll_number' => 'sometimes|string',
            'blood_group' => 'sometimes|string',
            'medical_info' => 'sometimes|string',
            'guardian_name' => 'sometimes|string',
            'guardian_phone' => 'sometimes|string',
            'guardian_email' => 'sometimes|email',
            'guardian_address' => 'sometimes|string',
            'emergency_contact' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update user data
            $userUpdateData = $request->only(['name', 'email', 'phone', 'address', 'date_of_birth', 'gender']);
            if (!empty($userUpdateData)) {
                $student->user->update($userUpdateData);
            }

            // Update student data
            $studentUpdateData = $request->only([
                'student_id', 'class_id', 'roll_number', 'blood_group', 'medical_info',
                'guardian_name', 'guardian_phone', 'guardian_email', 'guardian_address', 'emergency_contact'
            ]);
            if (!empty($studentUpdateData)) {
                $student->update($studentUpdateData);
            }

            DB::commit();

            $student->load('user', 'class');

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'data' => $student
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified student
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        try {
            // Soft delete by marking as inactive
            $student->update(['is_active' => false]);
            $student->user->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Student deactivated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student profile (for logged in student)
     */
    public function profile()
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $student = $user->student->load(['class', 'attendances.subject', 'examResults.exam.subject']);
        $student->attendance_percentage = $student->getAttendancePercentage();
        $student->grade_average = $student->getCurrentGradeAverage();

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Get students by class
     */
    public function getByClass($classId)
    {
        $class = SchoolClass::find($classId);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        $students = Student::with('user')
            ->where('class_id', $classId)
            ->active()
            ->orderBy('roll_number')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'class' => $class,
                'students' => $students
            ]
        ]);
    }

    /**
     * Bulk import students
     */
    public function bulkImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'students' => 'required|array',
            'students.*.name' => 'required|string',
            'students.*.email' => 'required|email|unique:users,email',
            'students.*.student_id' => 'required|string|unique:students,student_id',
            'students.*.class_id' => 'required|exists:classes,id',
            'students.*.guardian_name' => 'required|string',
            'students.*.guardian_phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $createdStudents = [];
            $errors = [];

            foreach ($request->students as $index => $studentData) {
                try {
                    // Create user
                    $user = User::create([
                        'name' => $studentData['name'],
                        'email' => $studentData['email'],
                        'password' => Hash::make('password123'), // Default password
                        'role' => 'student',
                        'phone' => $studentData['phone'] ?? null,
                        'date_of_birth' => $studentData['date_of_birth'] ?? null,
                        'gender' => $studentData['gender'] ?? null,
                    ]);

                    // Create student
                    $student = Student::create([
                        'user_id' => $user->id,
                        'student_id' => $studentData['student_id'],
                        'class_id' => $studentData['class_id'],
                        'admission_date' => $studentData['admission_date'] ?? now(),
                        'guardian_name' => $studentData['guardian_name'],
                        'guardian_phone' => $studentData['guardian_phone'],
                        'guardian_email' => $studentData['guardian_email'] ?? null,
                    ]);

                    $createdStudents[] = $student->load('user', 'class');
                } catch (\Exception $e) {
                    $errors[] = "Row {$index}: " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk import completed',
                'data' => [
                    'created' => $createdStudents,
                    'errors' => $errors,
                    'total_created' => count($createdStudents),
                    'total_errors' => count($errors)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Bulk import failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}