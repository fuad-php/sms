@extends('layouts.app')

@section('title', __('app.add'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('app.add') }} {{ __('app.image') }}</h1>
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
            <a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 rounded border">{{ __('app.cancel') }}</a>
            <button class="px-4 py-2 rounded bg-blue-600 text-white">{{ __('app.save') }}</button>
        </div>
    </form>
</div>
@endsection


