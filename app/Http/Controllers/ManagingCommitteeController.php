<?php

namespace App\Http\Controllers;

use App\Models\ManagingCommittee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ManagingCommitteeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-committees')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ManagingCommittee::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        $committees = $query->ordered()->paginate(15);

        $positions = ManagingCommittee::distinct()->pluck('position')->filter()->sort()->values();
        
        return view('managing-committees.index', compact('committees', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = [
            'Chairman' => __('app.chairman'),
            'Vice-Chairman' => __('app.vice_chairman'),
            'Secretary' => __('app.secretary'),
            'Treasurer' => __('app.treasurer'),
            'Member' => __('app.member'),
            'Advisor' => __('app.advisor'),
        ];

        return view('managing-committees.create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'term_start' => 'nullable|date',
            'term_end' => 'nullable|date|after_or_equal:term_start',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('committee-members', $filename, 'public');
            $data['image'] = $path;
        }

        // Set default sort order if not provided
        if (empty($data['sort_order'])) {
            $data['sort_order'] = ManagingCommittee::max('sort_order') + 1;
        }

        ManagingCommittee::create($data);

        return redirect()->route('managing-committees.index')
            ->with('success', __('app.committee_member_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ManagingCommittee $managingCommittee)
    {
        return view('managing-committees.show', compact('managingCommittee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ManagingCommittee $managingCommittee)
    {
        $positions = [
            'Chairman' => __('app.chairman'),
            'Vice-Chairman' => __('app.vice_chairman'),
            'Secretary' => __('app.secretary'),
            'Treasurer' => __('app.treasurer'),
            'Member' => __('app.member'),
            'Advisor' => __('app.advisor'),
        ];

        return view('managing-committees.edit', compact('managingCommittee', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManagingCommittee $managingCommittee)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'term_start' => 'nullable|date',
            'term_end' => 'nullable|date|after_or_equal:term_start',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($managingCommittee->image) {
                Storage::disk('public')->delete($managingCommittee->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('committee-members', $filename, 'public');
            $data['image'] = $path;
        }

        $managingCommittee->update($data);

        return redirect()->route('managing-committees.index')
            ->with('success', __('app.committee_member_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManagingCommittee $managingCommittee)
    {
        // Delete image if exists
        if ($managingCommittee->image) {
            Storage::disk('public')->delete($managingCommittee->image);
        }

        $managingCommittee->delete();

        return redirect()->route('managing-committees.index')
            ->with('success', __('app.committee_member_deleted_successfully'));
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(ManagingCommittee $managingCommittee)
    {
        $managingCommittee->update(['is_active' => !$managingCommittee->is_active]);

        $status = $managingCommittee->is_active ? __('app.activated') : __('app.deactivated');
        
        return redirect()->back()
            ->with('success', __('app.committee_member_status_updated', ['status' => $status]));
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(ManagingCommittee $managingCommittee)
    {
        $managingCommittee->update(['is_featured' => !$managingCommittee->is_featured]);

        $status = $managingCommittee->is_featured ? __('app.featured') : __('app.unfeatured');
        
        return redirect()->back()
            ->with('success', __('app.committee_member_featured_status_updated', ['status' => $status]));
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:managing_committees,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', __('app.invalid_action'));
        }

        $committees = ManagingCommittee::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'activate':
                $committees->update(['is_active' => true]);
                $message = __('app.selected_committees_activated');
                break;
            case 'deactivate':
                $committees->update(['is_active' => false]);
                $message = __('app.selected_committees_deactivated');
                break;
            case 'feature':
                $committees->update(['is_featured' => true]);
                $message = __('app.selected_committees_featured');
                break;
            case 'unfeature':
                $committees->update(['is_featured' => false]);
                $message = __('app.selected_committees_unfeatured');
                break;
            case 'delete':
                // Delete images
                $committees->get()->each(function ($committee) {
                    if ($committee->image) {
                        Storage::disk('public')->delete($committee->image);
                    }
                });
                $committees->delete();
                $message = __('app.selected_committees_deleted');
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}
