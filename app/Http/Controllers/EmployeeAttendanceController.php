<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = EmployeeAttendance::with('user');

        if ($request->filled('date')) {
            $query->forDate($request->date);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return response()->json($query->orderBy('date', 'desc')->paginate($request->integer('per_page', 20)));
    }

    public function mark(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,half_day,leave',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string',
        ]);

        $attendance = EmployeeAttendance::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'date' => $validated['date'],
            ],
            [
                'status' => $validated['status'],
                'check_in_time' => $validated['check_in_time'] ?? null,
                'check_out_time' => $validated['check_out_time'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ]
        );

        return response()->json(['success' => true, 'data' => $attendance->load('user')]);
    }
}


