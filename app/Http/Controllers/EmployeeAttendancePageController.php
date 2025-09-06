<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Models\User;

class EmployeeAttendancePageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = EmployeeAttendance::with('user')->orderByDesc('date');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date')) {
            $query->forDate($request->date);
        }

        $records = $query->paginate(20)->appends($request->query());
        $users = User::whereIn('role', ['teacher','staff','admin'])->orderBy('name')->get(['id','name']);

        return view('employee_attendance.index', [
            'records' => $records,
            'users' => $users,
            'pageTitle' => __('app.employee_attendance')
        ]);
    }
}


