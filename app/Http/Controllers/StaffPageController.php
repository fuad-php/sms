<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffPageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Staff::with('user')->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('employee_id', 'like', "%{$search}%");
        }

        $staff = $query->paginate(15)->appends($request->query());

        return view('staff.index', [
            'staff' => $staff,
            'pageTitle' => __('app.staff')
        ]);
    }

    public function create()
    {
        return view('staff.create', [
            'pageTitle' => __('app.add_staff')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
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
                'is_active' => $request->boolean('is_active', true),
            ]);

            Staff::create([
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
            return redirect()->route('staff.index')->with('success', __('app.created_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}


