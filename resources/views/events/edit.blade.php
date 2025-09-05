@extends('layouts.app')

@section('title', __('app.edit_event'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('app.edit_event') }}</h1>
    <form action="{{ route('events.update', $event) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('app.title') }}</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
            @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('app.description') }}</label>
            <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description', $event->description) }}</textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('app.location') }}</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}" class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('app.type') }}</label>
                <input type="text" name="type" value="{{ old('type', $event->type) }}" class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start</label>
                <input type="datetime-local" name="start_at" value="{{ old('start_at', $event->start_at ? $event->start_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                @error('start_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">End</label>
                <input type="datetime-local" name="end_at" value="{{ old('end_at', $event->end_at ? $event->end_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                @error('end_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('app.color') }}</label>
                <input type="text" name="color" value="{{ old('color', $event->color) }}" class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
            <div class="flex items-center mt-6">
                <input type="checkbox" name="is_published" id="is_published" class="mr-2" {{ old('is_published', $event->is_published) ? 'checked' : '' }}>
                <label for="is_published" class="text-sm text-gray-700">{{ __('app.publish_immediately') }}</label>
            </div>
        </div>
        <div class="flex justify-end space-x-3">
            <a href="{{ route('events.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">{{ __('app.cancel') }}</a>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">{{ __('app.update_event') }}</button>
        </div>
    </form>
</div>
@endsection


