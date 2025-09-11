<?php

namespace App\Http\Controllers;

use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeeCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FeeCategory::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $categories = $query->ordered()->paginate(15);

        return view('fees.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fees.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fee_categories,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        FeeCategory::create($request->all());

        return redirect()->route('fee-categories.index')
                        ->with('success', 'Fee category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeCategory $feeCategory)
    {
        $feeCategory->load(['feeStructures.class']);
        return view('fees.categories.show', compact('feeCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeCategory $feeCategory)
    {
        return view('fees.categories.edit', compact('feeCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeCategory $feeCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fee_categories,code,' . $feeCategory->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $feeCategory->update($request->all());

        return redirect()->route('fee-categories.index')
                        ->with('success', 'Fee category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeCategory $feeCategory)
    {
        // Check if category has fee structures
        if ($feeCategory->feeStructures()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing fee structures.');
        }

        $feeCategory->delete();

        return redirect()->route('fee-categories.index')
                        ->with('success', 'Fee category deleted successfully.');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(FeeCategory $feeCategory)
    {
        $feeCategory->update(['is_active' => !$feeCategory->is_active]);

        $status = $feeCategory->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Fee category {$status} successfully.");
    }
}
