<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarouselSlide;
use App\Models\CarouselSlideTranslation;
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
            // English (required)
            'title_en' => 'required|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'button_text_en' => 'nullable|string|max:100',
            // Bangla (optional)
            'title_bn' => 'nullable|string|max:255',
            'subtitle_bn' => 'nullable|string|max:255',
            'description_bn' => 'nullable|string',
            'button_text_bn' => 'nullable|string|max:100',
            // Common
            'button_url' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $data = [
            // Store English as base values for fallback
            'title' => $request->input('title_en'),
            'subtitle' => $request->input('subtitle_en'),
            'description' => $request->input('description_en'),
            'button_text' => $request->input('button_text_en'),
            'button_url' => $request->input('button_url'),
            'order' => $request->input('order'),
        ];
        
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

        $slide = CarouselSlide::create($data);

        // Save translations
        CarouselSlideTranslation::updateOrCreate(
            ['carousel_slide_id' => $slide->id, 'locale' => 'en'],
            [
                'title' => $request->input('title_en'),
                'subtitle' => $request->input('subtitle_en'),
                'description' => $request->input('description_en'),
                'button_text' => $request->input('button_text_en'),
            ]
        );

        if ($request->filled('title_bn') || $request->filled('subtitle_bn') || $request->filled('description_bn') || $request->filled('button_text_bn')) {
            CarouselSlideTranslation::updateOrCreate(
                ['carousel_slide_id' => $slide->id, 'locale' => 'bn'],
                [
                    'title' => $request->input('title_bn'),
                    'subtitle' => $request->input('subtitle_bn'),
                    'description' => $request->input('description_bn'),
                    'button_text' => $request->input('button_text_bn'),
                ]
            );
        }

        return redirect()->route('admin.carousel.index')
            ->with('success', __('app.slide_created_successfully'));
    }

    /**
     * Show the form for editing a carousel slide
     */
    public function edit(CarouselSlide $slide)
    {
        $slide->load('translations');
        return view('admin.carousel.edit', compact('slide'));
    }

    /**
     * Update the specified carousel slide
     */
    public function update(Request $request, CarouselSlide $slide)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'button_text_en' => 'nullable|string|max:100',
            'title_bn' => 'nullable|string|max:255',
            'subtitle_bn' => 'nullable|string|max:255',
            'description_bn' => 'nullable|string',
            'button_text_bn' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $request->input('title_en'),
            'subtitle' => $request->input('subtitle_en'),
            'description' => $request->input('description_en'),
            'button_text' => $request->input('button_text_en'),
            'button_url' => $request->input('button_url'),
            'order' => $request->input('order'),
        ];

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

        // Update translations
        CarouselSlideTranslation::updateOrCreate(
            ['carousel_slide_id' => $slide->id, 'locale' => 'en'],
            [
                'title' => $request->input('title_en'),
                'subtitle' => $request->input('subtitle_en'),
                'description' => $request->input('description_en'),
                'button_text' => $request->input('button_text_en'),
            ]
        );

        if ($request->filled('title_bn') || $request->filled('subtitle_bn') || $request->filled('description_bn') || $request->filled('button_text_bn')) {
            CarouselSlideTranslation::updateOrCreate(
                ['carousel_slide_id' => $slide->id, 'locale' => 'bn'],
                [
                    'title' => $request->input('title_bn'),
                    'subtitle' => $request->input('subtitle_bn'),
                    'description' => $request->input('description_bn'),
                    'button_text' => $request->input('button_text_bn'),
                ]
            );
        }

        return redirect()->route('admin.carousel.index')
            ->with('success', __('app.slide_updated_successfully'));
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
            ->with('success', __('app.slide_deleted_successfully'));
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

        return response()->json(['message' => __('app.order_updated_successfully')]);
    }

    /**
     * Toggle the active status of a carousel slide
     */
    public function toggleStatus(CarouselSlide $slide)
    {
        $slide->update(['is_active' => !$slide->is_active]);

        return redirect()->route('admin.carousel.index')
            ->with('success', __('app.slide_status_updated'));
    }

    /**
     * Get active carousel slides for the homepage
     */
    public function getActiveSlides()
    {
        $slides = CarouselSlide::with('translations')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->title_localized,
                    'subtitle' => $slide->subtitle_localized,
                    'description' => $slide->description_localized,
                    'button_text' => $slide->button_text_localized,
                    'button_url' => $slide->button_url,
                    'image_url' => $slide->image_url,
                    'order' => $slide->order,
                    'is_active' => $slide->is_active,
                ];
            });

        return response()->json($slides);
    }
}
