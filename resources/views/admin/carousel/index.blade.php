@extends('layouts.app')

@section('title', __('app.carousel_management'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('admin.carousel.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>{{ __('app.add_slide') }}
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($slides->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-images text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('app.no_carousel_slides') }}</h3>
            <p class="text-gray-600 mb-4">{{ __('app.no_data_found') }}</p>
            <a href="{{ route('admin.carousel.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>{{ __('app.create_first_slide') }}
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-images mr-2"></i>{{ __('app.carousel_slides') }}
                </h3>
                <p class="text-sm text-gray-600 mt-1">{{ $slides->count() }} slide{{ $slides->count() !== 1 ? 's' : '' }}</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.display_order') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.image') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.slide_title') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.slide_subtitle') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
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
                                {{ $slide->subtitle ?: __('app.not_available') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $slide->is_active ? __('app.active') : __('app.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.carousel.edit', $slide) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                       title="{{ __('app.edit') }}">
                                        <i class="fas fa-edit mr-1"></i>{{ __('app.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.carousel.toggle-status', $slide) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white {{ $slide->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $slide->is_active ? 'focus:ring-yellow-500' : 'focus:ring-green-500' }}"
                                                title="{{ $slide->is_active ? __('app.deactivate') : __('app.activate') }}">
                                            <i class="fas fa-toggle-{{ $slide->is_active ? 'on' : 'off' }} mr-1"></i>
                                            {{ $slide->is_active ? __('app.deactivate') : __('app.activate') }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.carousel.destroy', $slide) }}" class="inline" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                title="{{ __('app.delete') }}">
                                            <i class="fas fa-trash mr-1"></i>{{ __('app.delete') }}
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
                <i class="fas fa-eye mr-2"></i>{{ __('app.carousel_preview') }}
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
    </div>
</div>
@endsection
