<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Leave::with(['user']);

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        } else if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate($request->integer('per_page', 20)));
    }

    public function apply(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:casual,sick,earned,unpaid,maternity,paternity,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $leave = Leave::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'data' => $leave], 201);
    }

    public function approve(Request $request, Leave $leave)
    {
        $this->authorizeAdmin();

        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return response()->json(['success' => true, 'data' => $leave->fresh()]);
    }

    public function reject(Request $request, Leave $leave)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string',
        ]);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        return response()->json(['success' => true, 'data' => $leave->fresh()]);
    }

    private function authorizeAdmin(): void
    {
        if (!in_array(Auth::user()->role, ['admin'])) {
            abort(403, 'Unauthorized');
        }
    }
}


