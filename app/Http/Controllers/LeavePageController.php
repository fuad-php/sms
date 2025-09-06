<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;

class LeavePageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Leave::with(['user','approver'])->orderByDesc('created_at');
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
            $mode = 'my';
        } else if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
            $mode = 'all';
        } else {
            $mode = $user->role === 'admin' ? 'all' : 'my';
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pageName = ($mode === 'all') ? 'all_page' : 'my_page';
        $leaves = $query->paginate(20, ['*'], $pageName)
                        ->appends(collect($request->query())->except($pageName)->toArray());
        $users = $user->role === 'admin' ? User::orderBy('name')->get(['id','name']) : collect();

        return view('leaves.index', [
            'leaves' => $leaves,
            'users' => $users,
            'pageTitle' => __('app.leaves'),
            'mode' => $mode,
        ]);
    }

    public function my(Request $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        return $this->index($request);
    }

    public function all(Request $request)
    {
        $this->authorizeAdmin();
        // Explicitly set mode to 'all' by not forcing user_id
        return $this->index($request);
    }

    private function authorizeAdmin(): void
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:casual,sick,earned,unpaid,maternity,paternity,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        Leave::create([
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('leaves.my')->with('success', __('app.leave_applied_successfully'));
    }

    public function show(Leave $leave)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $leave->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $leave->load(['user','approver']);

        return view('leaves.show', [
            'leave' => $leave,
            'pageTitle' => __('app.leave_details'),
        ]);
    }
}


