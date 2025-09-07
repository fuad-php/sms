<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::featuredFirst()->paginate(24);
        return view('gallery.admin.index', compact('images'));
    }

    public function create()
    {
        $this->authorizeAdminOrStaff();
        return view('gallery.admin.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrStaff();
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|max:4096',
            'is_featured' => 'nullable',
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        GalleryImage::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_path' => $path,
            'is_featured' => $request->has('is_featured'),
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('admin.gallery.index')->with('success', __('app.operation_successful'));
    }

    public function edit(GalleryImage $galleryImage)
    {
        $this->authorizeAdmin();
        return view('gallery.admin.edit', ['image' => $galleryImage]);
    }

    public function update(Request $request, GalleryImage $galleryImage)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:4096',
            'is_featured' => 'nullable',
        ]);

        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'is_featured' => $request->has('is_featured'),
        ];

        if ($request->hasFile('image')) {
            // delete old
            if ($galleryImage->image_path) {
                Storage::disk('public')->delete($galleryImage->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $galleryImage->update($data);

        return redirect()->route('admin.gallery.index')->with('success', __('app.operation_successful'));
    }

    public function destroy(GalleryImage $galleryImage)
    {
        $this->authorizeAdmin();
        if ($galleryImage->image_path) {
            Storage::disk('public')->delete($galleryImage->image_path);
        }
        $galleryImage->delete();
        return redirect()->route('admin.gallery.index')->with('success', __('app.operation_successful'));
    }

    public function toggleFeatured(GalleryImage $galleryImage)
    {
        $this->authorizeAdmin();
        $galleryImage->update(['is_featured' => !$galleryImage->is_featured]);
        return back()->with('success', __('app.changes_saved'));
    }

    private function authorizeAdminOrStaff(): void
    {
        $role = Auth::user()->role ?? null;
        abort_unless(in_array($role, ['admin', 'teacher', 'staff']), 403);
    }

    private function authorizeAdmin(): void
    {
        $role = Auth::user()->role ?? null;
        abort_unless($role === 'admin', 403);
    }
}


