<?php

namespace App\Http\Controllers;

use App\Models\YearlyLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YearlyLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = YearlyLeave::query();

        // Filter by year
        if ($request->filled('year')) {
            $query->forYear($request->year);
        } else {
            $query->forYear(now()->year);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'current') {
                $query->current();
            } elseif ($request->status === 'upcoming') {
                $query->upcoming();
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $yearlyLeaves = $query->orderBy('start_date')->paginate(15);
        
        // Get available years for filter
        $availableYears = YearlyLeave::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('yearly-leaves.index', compact('yearlyLeaves', 'availableYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = [
            'holiday' => 'Holiday',
            'vacation' => 'Vacation',
            'exam_period' => 'Exam Period',
            'other' => 'Other'
        ];

        return view('yearly-leaves.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'year' => 'required|integer|min:2020|max:2030',
            'type' => 'required|in:holiday,vacation,exam_period,other',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        YearlyLeave::create($request->all());

        return redirect()->route('yearly-leaves.index')
            ->with('success', __('app.yearly_leave_setting_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(YearlyLeave $yearlyLeave)
    {
        return view('yearly-leaves.show', compact('yearlyLeave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YearlyLeave $yearlyLeave)
    {
        $types = [
            'holiday' => 'Holiday',
            'vacation' => 'Vacation',
            'exam_period' => 'Exam Period',
            'other' => 'Other'
        ];

        return view('yearly-leaves.edit', compact('yearlyLeave', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YearlyLeave $yearlyLeave)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'year' => 'required|integer|min:2020|max:2030',
            'type' => 'required|in:holiday,vacation,exam_period,other',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $yearlyLeave->update($request->all());

        return redirect()->route('yearly-leaves.index')
            ->with('success', __('app.yearly_leave_setting_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YearlyLeave $yearlyLeave)
    {
        $yearlyLeave->delete();

        return redirect()->route('yearly-leaves.index')
            ->with('success', __('app.yearly_leave_setting_deleted_successfully'));
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(YearlyLeave $yearlyLeave)
    {
        $yearlyLeave->update(['is_active' => !$yearlyLeave->is_active]);

        $status = $yearlyLeave->is_active ? __('app.activated') : __('app.deactivated');
        
        return redirect()->back()
            ->with('success', __('app.yearly_leave_setting_status_changed', ['status' => $status]));
    }
}
