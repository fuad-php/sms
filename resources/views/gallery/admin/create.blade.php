@extends('layouts.app')

@section('title', __('app.add_image'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('admin.gallery.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('app.cancel') }}
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('app.title') }}</label>
            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('app.description') }}</label>
            <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('app.image') }} *</label>
            <input type="file" name="image" accept="image/*" required class="mt-1 block w-full border-gray-300 rounded-md">
            @error('image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_featured" id="is_featured" class="mr-2" {{ old('is_featured') ? 'checked' : '' }}>
            <label for="is_featured" class="text-sm text-gray-700">{{ __('app.featured') }}</label>
        </div>
        <div class="flex justify-end space-x-2">
            <button class="px-4 py-2 rounded bg-blue-600 text-white">{{ __('app.save') }}</button>
        </div>
    </form>
        </div>
    </div>
</div>
@endsection


