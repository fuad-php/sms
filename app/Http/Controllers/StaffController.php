<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = Staff::with('user');

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('employee_id', 'like', "%{$search}%")
              ->orWhere('designation', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%");
        }

        $perPage = $request->integer('per_page', 15);
        $staff = $query->paginate($perPage)->appends($request->query());

        return response()->json($staff);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'employee_id' => 'required|string|unique:staff,employee_id',
            'designation' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric',
            'joining_date' => 'nullable|date',
            'experience' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'staff',
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'is_active' => $request->boolean('is_active', true),
            ]);

            $staff = Staff::create([
                'user_id' => $user->id,
                'employee_id' => $validated['employee_id'],
                'designation' => $validated['designation'] ?? null,
                'department' => $validated['department'] ?? null,
                'salary' => $validated['salary'] ?? null,
                'joining_date' => $validated['joining_date'] ?? null,
                'experience' => $validated['experience'] ?? null,
                'is_active' => $request->boolean('is_active', true),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'data' => $staff->load('user')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Staff $staff)
    {
        return response()->json($staff->load('user'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $staff->user_id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'employee_id' => 'sometimes|required|string|unique:staff,employee_id,' . $staff->id,
            'designation' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric',
            'joining_date' => 'nullable|date',
            'experience' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $userData = [];
            foreach (['name','email','phone','address'] as $key) {
                if (array_key_exists($key, $validated)) {
                    $userData[$key] = $validated[$key];
                }
            }
            if (!empty($validated['password'] ?? null)) {
                $userData['password'] = Hash::make($validated['password']);
            }
            if ($request->has('is_active')) {
                $userData['is_active'] = $request->boolean('is_active');
            }
            if (!empty($userData)) {
                $staff->user->update($userData);
            }

            $staff->update([
                'employee_id' => $validated['employee_id'] ?? $staff->employee_id,
                'designation' => $validated['designation'] ?? $staff->designation,
                'department' => $validated['department'] ?? $staff->department,
                'salary' => $validated['salary'] ?? $staff->salary,
                'joining_date' => $validated['joining_date'] ?? $staff->joining_date,
                'experience' => $validated['experience'] ?? $staff->experience,
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $staff->is_active,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'data' => $staff->load('user')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Staff $staff)
    {
        try {
            DB::beginTransaction();
            $staff->delete();
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}


