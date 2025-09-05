@extends('layouts.app')

@section('title', __('app.create_class'))

@section('content')
<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('classes.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Class</h1>
                    <p class="text-gray-600 mt-1">Add a new class to the school system</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm border">
            <form method="POST" action="{{ route('classes.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Class Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.class_name') }} *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="{{ __('app.enter_class_name') }}">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section -->
                <div>
                    <label for="section" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.section') }}</label>
                    <div class="flex space-x-2">
                        <input type="text" 
                               id="section" 
                               name="section" 
                               value="{{ old('section') }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('section') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_section_name') }}">
                        <button type="button" 
                                id="suggest-sections" 
                                class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 text-sm">
                            {{ __('app.suggest') }}
                        </button>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">{{ __('app.section_help_text') }}</p>
                    @error('section')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Section suggestions -->
                    <div id="section-suggestions" class="mt-2 hidden">
                        <div class="text-sm text-gray-600 mb-2">{{ __('app.common_sections') }}:</div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="section-suggestion px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs hover:bg-blue-200" data-section="A">A</button>
                            <button type="button" class="section-suggestion px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs hover:bg-blue-200" data-section="B">B</button>
                            <button type="button" class="section-suggestion px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs hover:bg-blue-200" data-section="C">C</button>
                            <button type="button" class="section-suggestion px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs hover:bg-blue-200" data-section="D">D</button>
                            <button type="button" class="section-suggestion px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs hover:bg-blue-200" data-section="Science">{{ __('app.science') }}</button>
                            <button type="button" class="section-suggestion px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs hover:bg-blue-200" data-section="Commerce">{{ __('app.commerce') }}</button>
                            <button type="button" class="section-suggestion px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs hover:bg-blue-200" data-section="Arts">{{ __('app.arts') }}</button>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Optional description for this class">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class Teacher -->
                <div>
                    <label for="class_teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Class Teacher</label>
                    <select id="class_teacher_id" 
                            name="class_teacher_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('class_teacher_id') border-red-500 @enderror">
                        <option value="">Select a teacher (optional)</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('class_teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }} ({{ $teacher->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('class_teacher_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity *</label>
                    <input type="number" 
                           id="capacity" 
                           name="capacity" 
                           value="{{ old('capacity', 30) }}"
                           min="1" 
                           max="100"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('capacity') border-red-500 @enderror"
                           placeholder="Maximum number of students">
                    @error('capacity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Maximum number of students that can be enrolled in this class</p>
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active (students can be enrolled in this class)
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('classes.index') }}" 
                       class="bg-white text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const suggestButton = document.getElementById('suggest-sections');
    const suggestionsDiv = document.getElementById('section-suggestions');
    const sectionInput = document.getElementById('section');
    const suggestionButtons = document.querySelectorAll('.section-suggestion');

    // Toggle suggestions visibility
    suggestButton.addEventListener('click', function() {
        suggestionsDiv.classList.toggle('hidden');
    });

    // Handle suggestion button clicks
    suggestionButtons.forEach(button => {
        button.addEventListener('click', function() {
            sectionInput.value = this.dataset.section;
            suggestionsDiv.classList.add('hidden');
            sectionInput.focus();
        });
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!suggestButton.contains(e.target) && !suggestionsDiv.contains(e.target)) {
            suggestionsDiv.classList.add('hidden');
        }
    });
});
</script>
@endsection
