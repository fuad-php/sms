@extends('layouts.app')

@section('title', __('app.edit_exam'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.edit_exam') }}</h1>
                    <p class="text-gray-600">{{ __('app.edit_exam_information') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('exams.show', $exam) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ __('app.view_exam') }}
                    </a>
                    <a href="{{ route('exams.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('app.back_to_exams') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
            <form method="POST" action="{{ route('exams.update', $exam) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Exam Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.exam_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $exam->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_exam_name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Exam Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.exam_type') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                            <option value="">{{ __('app.select_exam_type') }}</option>
                            <option value="quiz" {{ old('type', $exam->type) == 'quiz' ? 'selected' : '' }}>{{ __('app.quiz') }}</option>
                            <option value="midterm" {{ old('type', $exam->type) == 'midterm' ? 'selected' : '' }}>{{ __('app.midterm') }}</option>
                            <option value="final" {{ old('type', $exam->type) == 'final' ? 'selected' : '' }}>{{ __('app.final') }}</option>
                            <option value="assignment" {{ old('type', $exam->type) == 'assignment' ? 'selected' : '' }}>{{ __('app.assignment') }}</option>
                            <option value="project" {{ old('type', $exam->type) == 'project' ? 'selected' : '' }}>{{ __('app.project') }}</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.class') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="class_id" id="class_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('class_id') border-red-500 @enderror">
                            <option value="">{{ __('app.select_class') }}</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $exam->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }} - {{ $class->section }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.subject') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('subject_id') border-red-500 @enderror">
                            <option value="">{{ __('app.select_subject') }}</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.exam_date') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" value="{{ old('date', $exam->date ? $exam->date->format('Y-m-d') : '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.start_time') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $exam->start_time ? $exam->start_time->format('H:i') : '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-500 @enderror">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.duration_minutes') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration', $exam->duration) }}" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('duration') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_duration') }}">
                        @error('duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Marks -->
                    <div>
                        <label for="total_marks" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.total_marks') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="total_marks" id="total_marks" value="{{ old('total_marks', $exam->total_marks) }}" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('total_marks') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_total_marks') }}">
                        @error('total_marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pass Marks -->
                    <div>
                        <label for="pass_marks" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.pass_marks') }}
                        </label>
                        <input type="number" name="pass_marks" id="pass_marks" value="{{ old('pass_marks', $exam->pass_marks) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('pass_marks') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_pass_marks') }}">
                        @error('pass_marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room -->
                    <div>
                        <label for="room" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.exam_room') }}
                        </label>
                        <input type="text" name="room" id="room" value="{{ old('room', $exam->room) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('room') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_room_number') }}">
                        @error('room')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructions -->
                    <div class="md:col-span-2">
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.instructions') }}
                        </label>
                        <textarea name="instructions" id="instructions" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('instructions') border-red-500 @enderror"
                                  placeholder="{{ __('app.enter_exam_instructions') }}">{{ old('instructions', $exam->instructions) }}</textarea>
                        @error('instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publish Status -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $exam->is_published) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">
                                {{ __('app.publish_exam_immediately') }}
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">{{ __('app.publish_exam_description') }}</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('exams.show', $exam) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('app.update_exam') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
