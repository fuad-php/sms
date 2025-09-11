@extends('layouts.app')

@section('title', __('app.edit_schedule'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('app.edit_schedule') }}</h1>
                <p class="mt-2 text-gray-600">{{ __('app.update_teacher_schedule') }}</p>
            </div>
            <a href="{{ route('teacher-scheduling.dashboard') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('app.back_to_dashboard') }}
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.schedule_details') }}</h3>
            </div>

            <form method="POST" action="{{ route('teacher-scheduling.update', $schedule) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Teacher Selection -->
                    <div>
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.teacher') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="teacher_id" id="teacher_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('teacher_id') border-red-500 @enderror">
                            <option value="">{{ __('app.select_teacher') }}</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->user_id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->user_id ? 'selected' : '' }}>
                                    {{ $teacher->getFullNameAttribute() }} - {{ $teacher->department ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Selection -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.class') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="class_id" id="class_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('class_id') border-red-500 @enderror">
                            <option value="">{{ __('app.select_class') }}</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $schedule->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject Selection -->
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.subject') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject_id') border-red-500 @enderror">
                            <option value="">{{ __('app.select_subject') }}</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $schedule->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Day of Week -->
                    <div>
                        <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.day_of_week') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="day_of_week" id="day_of_week" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('day_of_week') border-red-500 @enderror">
                            <option value="">{{ __('app.select_day') }}</option>
                            <option value="monday" {{ old('day_of_week', $schedule->day_of_week) == 'monday' ? 'selected' : '' }}>{{ __('app.monday') }}</option>
                            <option value="tuesday" {{ old('day_of_week', $schedule->day_of_week) == 'tuesday' ? 'selected' : '' }}>{{ __('app.tuesday') }}</option>
                            <option value="wednesday" {{ old('day_of_week', $schedule->day_of_week) == 'wednesday' ? 'selected' : '' }}>{{ __('app.wednesday') }}</option>
                            <option value="thursday" {{ old('day_of_week', $schedule->day_of_week) == 'thursday' ? 'selected' : '' }}>{{ __('app.thursday') }}</option>
                            <option value="friday" {{ old('day_of_week', $schedule->day_of_week) == 'friday' ? 'selected' : '' }}>{{ __('app.friday') }}</option>
                            <option value="saturday" {{ old('day_of_week', $schedule->day_of_week) == 'saturday' ? 'selected' : '' }}>{{ __('app.saturday') }}</option>
                            <option value="sunday" {{ old('day_of_week', $schedule->day_of_week) == 'sunday' ? 'selected' : '' }}>{{ __('app.sunday') }}</option>
                        </select>
                        @error('day_of_week')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.start_time') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_time') border-red-500 @enderror">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.end_time') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_time') border-red-500 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room Number -->
                    <div>
                        <label for="room_number" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.room_number') }}
                        </label>
                        <input type="text" name="room_number" id="room_number" value="{{ old('room_number', $schedule->room_number) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('room_number') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_room_number') }}">
                        @error('room_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Schedule Type -->
                    <div>
                        <label for="schedule_type" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.schedule_type') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="schedule_type" id="schedule_type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('schedule_type') border-red-500 @enderror">
                            <option value="">{{ __('app.select_type') }}</option>
                            <option value="regular" {{ old('schedule_type', $schedule->schedule_type) == 'regular' ? 'selected' : '' }}>{{ __('app.regular') }}</option>
                            <option value="substitute" {{ old('schedule_type', $schedule->schedule_type) == 'substitute' ? 'selected' : '' }}>{{ __('app.substitute') }}</option>
                            <option value="extra" {{ old('schedule_type', $schedule->schedule_type) == 'extra' ? 'selected' : '' }}>{{ __('app.extra') }}</option>
                            <option value="remedial" {{ old('schedule_type', $schedule->schedule_type) == 'remedial' ? 'selected' : '' }}>{{ __('app.remedial') }}</option>
                            <option value="exam_prep" {{ old('schedule_type', $schedule->schedule_type) == 'exam_prep' ? 'selected' : '' }}>{{ __('app.exam_prep') }}</option>
                        </select>
                        @error('schedule_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Effective From -->
                    <div>
                        <label for="effective_from" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.effective_from') }}
                        </label>
                        <input type="date" name="effective_from" id="effective_from" value="{{ old('effective_from', $schedule->effective_from?->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('effective_from') border-red-500 @enderror">
                        @error('effective_from')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Effective Until -->
                    <div>
                        <label for="effective_until" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.effective_until') }}
                        </label>
                        <input type="date" name="effective_until" id="effective_until" value="{{ old('effective_until', $schedule->effective_until?->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('effective_until') border-red-500 @enderror">
                        @error('effective_until')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('app.schedule_notes') }}
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                              placeholder="{{ __('app.enter_schedule_notes') }}">{{ old('notes', $schedule->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conflict Check -->
                <div id="conflict-check" class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">{{ __('app.schedule_conflicts') }}</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p id="conflict-message"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('teacher-scheduling.dashboard') }}" 
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        {{ __('app.update_schedule') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.getElementById('teacher_id');
    const daySelect = document.getElementById('day_of_week');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const conflictCheck = document.getElementById('conflict-check');
    const conflictMessage = document.getElementById('conflict-message');

    function checkConflicts() {
        const teacherId = teacherSelect.value;
        const day = daySelect.value;
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        if (teacherId && day && startTime && endTime) {
            fetch(`{{ route('teacher-scheduling.check-conflicts') }}?teacher_id=${teacherId}&day=${day}&start_time=${startTime}&end_time=${endTime}&exclude_id={{ $schedule->id }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.has_conflict) {
                        conflictMessage.textContent = data.conflicts.map(conflict => 
                            `${conflict.class} - ${conflict.subject} (${conflict.time})`
                        ).join(', ');
                        conflictCheck.classList.remove('hidden');
                    } else {
                        conflictCheck.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error checking conflicts:', error);
                });
        } else {
            conflictCheck.classList.add('hidden');
        }
    }

    teacherSelect.addEventListener('change', checkConflicts);
    daySelect.addEventListener('change', checkConflicts);
    startTimeInput.addEventListener('change', checkConflicts);
    endTimeInput.addEventListener('change', checkConflicts);
});
</script>
@endsection
