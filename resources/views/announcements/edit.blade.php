@extends('layouts.app')

@section('title', __('app.edit_announcement'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('announcements.show', $announcement) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('app.back_to_announcement') }}
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('announcements.update', $announcement) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.title') }} *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_announcement_title') }}">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="lg:col-span-2">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.content') }} *</label>
                        <textarea name="content" id="content" rows="8" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                                  placeholder="{{ __('app.enter_announcement_content') }}">{{ old('content', $announcement->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Audience -->
                    <div>
                        <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.target_audience') }} *</label>
                        <select name="target_audience" id="target_audience" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('target_audience') border-red-500 @enderror">
                            @foreach($audiences as $audience)
                                <option value="{{ $audience }}" {{ old('target_audience', $announcement->target_audience) == $audience ? 'selected' : '' }}>
                                    {{ __('app.audience_' . $audience) }}
                                </option>
                            @endforeach
                        </select>
                        @error('target_audience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class (Optional) -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.class_name') }} ({{ __('app.optional') }})</label>
                        <select name="class_id" id="class_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('class_id') border-red-500 @enderror">
                            <option value="">{{ __('app.all_classes') }}</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $announcement->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.priority') }} *</label>
                        <select name="priority" id="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                            @foreach($priorities as $priority)
                                <option value="{{ $priority }}" {{ old('priority', $announcement->priority) == $priority ? 'selected' : '' }}>
                                    {{ __('app.priority_' . $priority) }}
                                </option>
                            @endforeach
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publish Date -->
                    <div>
                        <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.publish_date') }} ({{ __('app.optional') }})</label>
                        <input type="datetime-local" name="publish_date" id="publish_date" 
                               value="{{ old('publish_date', $announcement->publish_date ? $announcement->publish_date->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('publish_date') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">{{ __('app.leave_empty_to_publish_immediately') }}</p>
                        @error('publish_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expire Date -->
                    <div>
                        <label for="expire_date" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.expire_date') }} ({{ __('app.optional') }})</label>
                        <input type="datetime-local" name="expire_date" id="expire_date" 
                               value="{{ old('expire_date', $announcement->expire_date ? $announcement->expire_date->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('expire_date') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">{{ __('app.leave_empty_for_no_expiration') }}</p>
                        @error('expire_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Attachment -->
                    @if($announcement->hasAttachment())
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.current_attachment') }}</label>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-paperclip text-2xl text-gray-500"></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $announcement->attachment }}</p>
                                    <p class="text-sm text-gray-600">{{ __('app.current_attachment') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('announcements.download', $announcement) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download mr-1"></i>{{ __('app.download') }}
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- New Attachment -->
                    <div class="lg:col-span-2">
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $announcement->hasAttachment() ? __('app.replace_attachment') : __('app.attachment') . ' (' . __('app.optional') . ')' }}
                        </label>
                        <input type="file" name="attachment" id="attachment"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('attachment') border-red-500 @enderror"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <p class="mt-1 text-xs text-gray-500">
                            {{ __('app.supported_formats_max') }}
                            @if($announcement->hasAttachment())
                                <br>{{ __('app.leave_empty_keep_current_attachment') }}
                            @endif
                        </p>
                        @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publish Status -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_published" id="is_published" value="1" 
                                   {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_published" class="ml-2 block text-sm text-gray-700">
                                {{ __('app.publish_announcement') }}
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">{{ __('app.uncheck_to_save_as_draft') }}</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('announcements.show', $announcement) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-save mr-2"></i>{{ __('app.update_announcement') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for publish_date to today
    const publishDateInput = document.getElementById('publish_date');
    const today = new Date().toISOString().slice(0, 16);
    publishDateInput.min = today;

    // Update expire_date minimum when publish_date changes
    publishDateInput.addEventListener('change', function() {
        const expireDateInput = document.getElementById('expire_date');
        if (this.value) {
            expireDateInput.min = this.value;
        }
    });
});
</script>
        </div>
    </div>
</div>
@endsection
