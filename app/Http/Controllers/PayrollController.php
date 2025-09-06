<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Payroll::with('user');

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        } else if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->integer('month'));
        }
        if ($request->filled('year')) {
            $query->where('year', $request->integer('year'));
        }

        return response()->json($query->orderBy('year', 'desc')->orderBy('month', 'desc')->paginate($request->integer('per_page', 20)));
    }

    public function generate(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000',
            'basic_salary' => 'required|numeric',
            'allowances' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $allowances = $validated['allowances'] ?? 0;
        $deductions = $validated['deductions'] ?? 0;
        $net = ($validated['basic_salary'] + $allowances) - $deductions;

        $payroll = Payroll::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'month' => $validated['month'],
                'year' => $validated['year'],
            ],
            [
                'basic_salary' => $validated['basic_salary'],
                'allowances' => $allowances,
                'deductions' => $deductions,
                'net_salary' => $net,
                'generated_by' => Auth::id(),
                'generated_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return response()->json(['success' => true, 'data' => $payroll->load('user')], 201);
    }

    private function authorizeAdmin(): void
    {
        if (!in_array(Auth::user()->role, ['admin'])) {
            abort(403, 'Unauthorized');
        }
    }
}


