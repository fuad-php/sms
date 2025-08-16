@extends('layouts.app')

@section('title', 'Carousel Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Carousel Management</h1>
                <p class="text-gray-600">Manage homepage carousel slides</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.carousel.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Add Slide
                </a>
            </div>
        </div>
    </div>

    @if($slides->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-images text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Carousel Slides</h3>
            <p class="text-gray-600 mb-4">No carousel slides have been created yet.</p>
            <a href="{{ route('admin.carousel.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Create First Slide
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-images mr-2"></i>Carousel Slides
                </h3>
                <p class="text-sm text-gray-600 mt-1">{{ $slides->count() }} slide{{ $slides->count() !== 1 ? 's' : '' }}</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtitle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($slides as $slide)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $slide->order }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($slide->image)
                                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="h-12 w-20 object-cover rounded">
                                @else
                                    <div class="h-12 w-20 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $slide->title }}</div>
                                @if($slide->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($slide->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $slide->subtitle ?: 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $slide->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.carousel.edit', $slide) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.carousel.toggle-status', $slide) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-toggle-{{ $slide->is_active ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.carousel.destroy', $slide) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this slide?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-eye mr-2"></i>Carousel Preview
            </h3>
            <div class="relative h-64 bg-gray-100 rounded-lg overflow-hidden">
                <div class="carousel-preview relative h-full">
                    @foreach($slides->where('is_active', true) as $index => $slide)
                        <div class="carousel-slide absolute inset-0 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-500" style="background-image: url('{{ $slide->image_url }}')">
                            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                            <div class="relative z-10 flex items-center justify-center h-full">
                                <div class="text-center text-white">
                                    <h2 class="text-2xl font-bold mb-2">{{ $slide->title }}</h2>
                                    @if($slide->subtitle)
                                        <p class="text-lg mb-4">{{ $slide->subtitle }}</p>
                                    @endif
                                    @if($slide->button_text && $slide->button_url)
                                        <a href="{{ $slide->button_url }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                            {{ $slide->button_text }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple carousel preview functionality
    const slides = document.querySelectorAll('.carousel-preview .carousel-slide');
    let currentSlide = 0;
    
    if (slides.length > 1) {
        setInterval(() => {
            slides[currentSlide].classList.remove('opacity-100');
            slides[currentSlide].classList.add('opacity-0');
            
            currentSlide = (currentSlide + 1) % slides.length;
            
            slides[currentSlide].classList.remove('opacity-0');
            slides[currentSlide].classList.add('opacity-100');
        }, 3000);
    }
});
</script>
@endpush
@endsection
