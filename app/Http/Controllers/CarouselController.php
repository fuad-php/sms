<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarouselSlide;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    /**
     * Display the carousel management page
     */
    public function index()
    {
        $slides = CarouselSlide::orderBy('order')->get();
        return view('admin.carousel.index', compact('slides'));
    }

    /**
     * Show the form for creating a new carousel slide
     */
    public function create()
    {
        return view('admin.carousel.create');
    }

    /**
     * Store a newly created carousel slide
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('carousel', 'public');
            $data['image'] = $imagePath;
        }

        // Set default order if not provided
        if (empty($data['order'])) {
            $data['order'] = CarouselSlide::max('order') + 1;
        }

        $data['is_active'] = $request->has('is_active');

        CarouselSlide::create($data);

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Carousel slide created successfully!');
    }

    /**
     * Show the form for editing a carousel slide
     */
    public function edit(CarouselSlide $slide)
    {
        return view('admin.carousel.edit', compact('slide'));
    }

    /**
     * Update the specified carousel slide
     */
    public function update(Request $request, CarouselSlide $slide)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($slide->image) {
                Storage::disk('public')->delete($slide->image);
            }
            
            $imagePath = $request->file('image')->store('carousel', 'public');
            $data['image'] = $imagePath;
        }

        $data['is_active'] = $request->has('is_active');

        $slide->update($data);

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Carousel slide updated successfully!');
    }

    /**
     * Remove the specified carousel slide
     */
    public function destroy(CarouselSlide $slide)
    {
        // Delete image file
        if ($slide->image) {
            Storage::disk('public')->delete($slide->image);
        }

        $slide->delete();

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Carousel slide deleted successfully!');
    }

    /**
     * Update the order of carousel slides
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'slides' => 'required|array',
            'slides.*.id' => 'required|exists:carousel_slides,id',
            'slides.*.order' => 'required|integer|min:1',
        ]);

        foreach ($request->slides as $slideData) {
            CarouselSlide::where('id', $slideData['id'])
                ->update(['order' => $slideData['order']]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Toggle the active status of a carousel slide
     */
    public function toggleStatus(CarouselSlide $slide)
    {
        $slide->update(['is_active' => !$slide->is_active]);

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Slide status updated successfully!');
    }

    /**
     * Get active carousel slides for the homepage
     */
    public function getActiveSlides()
    {
        $slides = CarouselSlide::where('is_active', true)
            ->orderBy('order')
            ->get();

        return response()->json($slides);
    }
}
