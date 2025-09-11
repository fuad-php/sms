@extends('layouts.app')

@section('title', __('app.timing_settings'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.timing_settings') }}</h1>
                    <p class="text-gray-600">{{ __('app.manage_class_timing_and_schedule_settings') }}</p>
                </div>
                <a href="{{ route('settings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    {{ __('app.back_to_settings') }}
                </a>
            </div>
        </div>

        <!-- Timing Settings Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.class_timing_settings') }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ __('app.configure_class_schedule_timing') }}</p>
            </div>

            <form method="POST" action="{{ route('settings.timing.update') }}" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Class Start Time -->
                    <div>
                        <label for="class_start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.class_start_time') }}
                        </label>
                        <input type="time" 
                               id="class_start_time" 
                               name="class_start_time" 
                               value="{{ \App\Helpers\SettingsHelper::getClassStartTime() }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.time_when_classes_start_each_day') }}</p>
                    </div>

                    <!-- Class Interval Minutes -->
                    <div>
                        <label for="class_interval_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.interval_time_minutes') }}
                        </label>
                        <input type="number" 
                               id="class_interval_minutes" 
                               name="class_interval_minutes" 
                               value="{{ \App\Helpers\SettingsHelper::getClassIntervalMinutes() }}"
                               min="1" 
                               max="60"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.time_interval_between_classes') }}</p>
                    </div>

                    <!-- Class Duration Minutes -->
                    <div>
                        <label for="class_duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.per_class_duration_minutes') }}
                        </label>
                        <input type="number" 
                               id="class_duration_minutes" 
                               name="class_duration_minutes" 
                               value="{{ \App\Helpers\SettingsHelper::getClassDurationMinutes() }}"
                               min="15" 
                               max="180"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.duration_of_each_class') }}</p>
                    </div>

                    <!-- Weekly Off Days -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.weekly_off_days') }}
                        </label>
                        <div class="space-y-2">
                            @foreach(\App\Helpers\SettingsHelper::getWeeklyOffDaysOptions() as $value => $label)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="weekly_offdays[]" 
                                           value="{{ $value }}"
                                           {{ in_array($value, \App\Helpers\SettingsHelper::getWeeklyOffDays()) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.days_when_school_is_closed') }}</p>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">{{ __('app.schedule_preview') }}</h4>
                    <div id="schedule-preview" class="text-sm text-gray-600">
                        <!-- Preview will be generated by JavaScript -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        {{ __('app.update_timing_settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('class_start_time');
    const intervalInput = document.getElementById('class_interval_minutes');
    const durationInput = document.getElementById('class_duration_minutes');
    const previewDiv = document.getElementById('schedule-preview');

    function updatePreview() {
        const startTime = startTimeInput.value || '08:00';
        const interval = parseInt(intervalInput.value) || 15;
        const duration = parseInt(durationInput.value) || 45;
        
        if (!startTime) return;

        const start = new Date('2000-01-01T' + startTime);
        let current = new Date(start);
        let preview = '<div class="space-y-1">';
        
        for (let i = 0; i < 8; i++) {
            const end = new Date(current.getTime() + duration * 60000);
            const startStr = current.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: {{ \App\Helpers\SettingsHelper::getTimeFormat() === '12' ? 'true' : 'false' }}
            });
            const endStr = end.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: {{ \App\Helpers\SettingsHelper::getTimeFormat() === '12' ? 'true' : 'false' }}
            });
            
            preview += `<div class="flex justify-between">
                <span>Period ${i + 1}:</span>
                <span>${startStr} - ${endStr}</span>
            </div>`;
            
            current = new Date(current.getTime() + interval * 60000);
        }
        
        preview += '</div>';
        previewDiv.innerHTML = preview;
    }

    startTimeInput.addEventListener('change', updatePreview);
    intervalInput.addEventListener('input', updatePreview);
    durationInput.addEventListener('input', updatePreview);

    // Initial preview
    updatePreview();
});
</script>
@endpush
@endsection
