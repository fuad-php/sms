@extends('layouts.app')

@section('title', __('app.create_event'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('app.create_event') }}</h1>
    <form action="{{ route('events.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('app.title') }}</label>
            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
            @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('app.description') }}</label>
            <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description') }}</textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('app.location') }}</label>
                <input type="text" name="location" value="{{ old('location') }}" class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('app.type') }}</label>
                <input type="text" name="type" value="{{ old('type') }}" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="sports, academic, meeting...">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start</label>
                <input type="datetime-local" name="start_at" value="{{ old('start_at') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                @error('start_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">End</label>
                <input type="datetime-local" name="end_at" value="{{ old('end_at') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                @error('end_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('app.color') }}</label>
                <input type="text" name="color" value="{{ old('color') }}" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="green, blue, purple...">
            </div>
            <div class="flex items-center mt-6">
                <input type="checkbox" name="is_published" id="is_published" class="mr-2" {{ old('is_published', true) ? 'checked' : '' }}>
                <label for="is_published" class="text-sm text-gray-700">{{ __('app.publish_immediately') }}</label>
            </div>
        </div>
        <div class="flex justify-end space-x-3">
            <a href="{{ route('events.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">{{ __('app.cancel') }}</a>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">{{ __('app.create_event') }}</button>
        </div>
    </form>
</div>
@endsection


