<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ParentModel::with(['user', 'students']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by occupation
        if ($request->filled('occupation')) {
            $query->where('occupation', 'like', "%{$request->occupation}%");
        }

        $parents = $query->paginate(15);

        // Get statistics
        $statistics = [
            'total_parents' => ParentModel::count(),
            'active_parents' => ParentModel::active()->count(),
            'parents_with_children' => ParentModel::whereHas('students')->count(),
            'new_this_month' => ParentModel::whereMonth('created_at', now()->month)->count(),
        ];

        // Get filter options
        $occupations = ParentModel::distinct()->pluck('occupation')->filter()->sort()->values();

        return view('parents.index', compact('parents', 'statistics', 'occupations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with('class')->get();
        return view('parents.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'occupation' => 'nullable|string|max:255',
            'workplace' => 'nullable|string|max:255',
            'income_range' => 'nullable|string|in:low,medium,high',
            'notes' => 'nullable|string',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id',
            'relationships' => 'nullable|array',
            'relationships.*' => 'string|in:father,mother,guardian,other',
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'parent',
        ]);

        // Create parent profile
        $parent = ParentModel::create([
            'user_id' => $user->id,
            'occupation' => $request->occupation,
            'workplace' => $request->workplace,
            'income_range' => $request->income_range,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        // Attach students if provided
        if ($request->filled('students')) {
            $studentsData = [];
            foreach ($request->students as $index => $studentId) {
                $studentsData[$studentId] = [
                    'relationship' => $request->relationships[$index] ?? 'guardian',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $parent->students()->attach($studentsData);
        }

        return redirect()->route('parents.show', $parent)
                        ->with('success', 'Parent created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParentModel $parent)
    {
        $parent->load(['user', 'students.class', 'students.attendances' => function ($query) {
            $query->whereMonth('date', now()->month);
        }]);

        // Get recent attendance data for children
        $childrenAttendance = $parent->getAllChildrenAttendance(now()->startOfMonth(), now()->endOfMonth());
        
        // Get recent grades for children
        $childrenGrades = $parent->getAllChildrenGrades();

        return view('parents.show', compact('parent', 'childrenAttendance', 'childrenGrades'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParentModel $parent)
    {
        $parent->load(['user', 'students']);
        $students = Student::with('class')->get();
        return view('parents.edit', compact('parent', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParentModel $parent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($parent->user_id),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'occupation' => 'nullable|string|max:255',
            'workplace' => 'nullable|string|max:255',
            'income_range' => 'nullable|string|in:low,medium,high',
            'notes' => 'nullable|string',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id',
            'relationships' => 'nullable|array',
            'relationships.*' => 'string|in:father,mother,guardian,other',
        ]);

        // Update user account
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $parent->user->update($userData);

        // Update parent profile
        $parent->update([
            'occupation' => $request->occupation,
            'workplace' => $request->workplace,
            'income_range' => $request->income_range,
            'notes' => $request->notes,
        ]);

        // Update student relationships
        if ($request->has('students')) {
            $studentsData = [];
            foreach ($request->students as $index => $studentId) {
                $studentsData[$studentId] = [
                    'relationship' => $request->relationships[$index] ?? 'guardian',
                    'updated_at' => now(),
                ];
            }
            $parent->students()->sync($studentsData);
        } else {
            $parent->students()->detach();
        }

        return redirect()->route('parents.show', $parent)
                        ->with('success', 'Parent updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParentModel $parent)
    {
        // Delete user account (this will cascade to parent profile)
        $parent->user->delete();

        return redirect()->route('parents.index')
                        ->with('success', 'Parent deleted successfully.');
    }

    /**
     * Toggle parent status
     */
    public function toggleStatus(ParentModel $parent)
    {
        $parent->update(['is_active' => !$parent->is_active]);

        $status = $parent->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
                        ->with('success', "Parent {$status} successfully.");
    }

    /**
     * Get parent statistics
     */
    public function statistics()
    {
        return response()->json([
            'total_parents' => ParentModel::count(),
            'active_parents' => ParentModel::active()->count(),
            'parents_with_children' => ParentModel::whereHas('students')->count(),
            'new_this_month' => ParentModel::whereMonth('created_at', now()->month)->count(),
            'by_occupation' => ParentModel::selectRaw('occupation, COUNT(*) as count')
                                        ->whereNotNull('occupation')
                                        ->groupBy('occupation')
                                        ->orderBy('count', 'desc')
                                        ->get(),
        ]);
    }

    /**
     * Get parent's children
     */
    public function getChildren(ParentModel $parent)
    {
        $children = $parent->students()->with('class')->get();
        return response()->json($children);
    }

    /**
     * Assign students to parent
     */
    public function assignStudents(Request $request, ParentModel $parent)
    {
        $request->validate([
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
            'relationships' => 'required|array',
            'relationships.*' => 'string|in:father,mother,guardian,other',
        ]);

        $studentsData = [];
        foreach ($request->students as $index => $studentId) {
            $studentsData[$studentId] = [
                'relationship' => $request->relationships[$index],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $parent->students()->sync($studentsData);

        return redirect()->back()
                        ->with('success', 'Students assigned successfully.');
    }
}
