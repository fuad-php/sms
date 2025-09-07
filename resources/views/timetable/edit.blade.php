@extends('layouts.app')

@section('title', __('app.edit_timetable'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.edit_timetable') }}</h1>
                    <p class="text-gray-600">{{ __('app.update_timetable_entry') }}</p>
                </div>
                <a href="{{ route('timetable.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← {{ __('app.back_to_timetable') }}
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('timetable.update', $timetable) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Class Selection -->
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700">{{ __('app.class') }} <span class="text-red-500">*</span></label>
                    <select name="class_id" id="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('class_id') border-red-500 @enderror">
                        <option value="">{{ __('app.select_class') }}</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id', $timetable->class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section Selection (optional) -->
                <div>
                    <label for="section_id" class="block text-sm font-medium text-gray-700">{{ __('app.section') }}</label>
                    <select name="section_id" id="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('section_id') border-red-500 @enderror">
                        <option value="">{{ __('app.select_section') }}</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id', $timetable->section_id) == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Selection -->
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700">{{ __('app.subject') }} <span class="text-red-500">*</span></label>
                    <select name="subject_id" id="subject_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('subject_id') border-red-500 @enderror">
                        <option value="">{{ __('app.select_subject') }}</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $timetable->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teacher Selection -->
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700">{{ __('app.teacher') }} <span class="text-red-500">*</span></label>
                    <select name="teacher_id" id="teacher_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('teacher_id') border-red-500 @enderror">
                        <option value="">{{ __('app.select_teacher') }}</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $timetable->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Day of Week -->
                <div>
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700">{{ __('app.day_of_week') }} <span class="text-red-500">*</span></label>
                    <select name="day_of_week" id="day_of_week" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('day_of_week') border-red-500 @enderror">
                        <option value="">{{ __('app.select_day') }}</option>
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ old('day_of_week', $timetable->day_of_week) == $day ? 'selected' : '' }}>
                                {{ ucfirst($day) }}
                            </option>
                        @endforeach
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Selection -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">{{ __('app.start_time') }} <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" id="start_time" required value="{{ old('start_time', $timetable->start_time->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_time') border-red-500 @enderror">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">{{ __('app.end_time') }} <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" id="end_time" required value="{{ old('end_time', $timetable->end_time->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('end_time') border-red-500 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Room Selection (optional) -->
                <div>
                    <label for="room_id" class="block text-sm font-medium text-gray-700">{{ __('app.room') }}</label>
                    <select name="room_id" id="room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('room_id') border-red-500 @enderror">
                        <option value="">{{ __('app.select_room') }}</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $timetable->room_id) == $room->id ? 'selected' : '' }}>
                                {{ $room->name }} @if($room->code) ({{ $room->code }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $timetable->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">{{ __('app.active') }}</label>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">{{ __('app.there_were_errors') }}</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('timetable.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md">
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ __('app.update_timetable') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
