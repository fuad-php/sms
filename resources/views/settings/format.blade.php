@extends('layouts.app')

@section('title', __('app.format_settings'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.format_settings') }}</h1>
                    <p class="text-gray-600">{{ __('app.manage_date_time_format_and_timezone_settings') }}</p>
                </div>
                <a href="{{ route('settings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    {{ __('app.back_to_settings') }}
                </a>
            </div>
        </div>

        <!-- Format Settings Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.date_time_format_settings') }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ __('app.configure_how_dates_and_times_are_displayed') }}</p>
            </div>

            <form method="POST" action="{{ route('settings.format.update') }}" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date Format -->
                    <div>
                        <label for="date_format" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.date_format') }}
                        </label>
                        <select id="date_format" 
                                name="date_format" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(\App\Helpers\SettingsHelper::getDateFormatOptions() as $value => $label)
                                <option value="{{ $value }}" {{ \App\Helpers\SettingsHelper::getDateFormat() === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.how_dates_are_displayed_throughout_app') }}</p>
                    </div>

                    <!-- Time Format -->
                    <div>
                        <label for="time_format" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.time_format') }}
                        </label>
                        <select id="time_format" 
                                name="time_format" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(\App\Helpers\SettingsHelper::getTimeFormatOptions() as $value => $label)
                                <option value="{{ $value }}" {{ \App\Helpers\SettingsHelper::getTimeFormat() === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.how_times_are_displayed_throughout_app') }}</p>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.timezone') }}
                        </label>
                        <select id="timezone" 
                                name="timezone" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(\App\Helpers\SettingsHelper::getTimezoneOptions() as $value => $label)
                                <option value="{{ $value }}" {{ \App\Helpers\SettingsHelper::getTimezone() === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.default_timezone_for_application') }}</p>
                    </div>

                    <!-- Timezone Offset -->
                    <div>
                        <label for="timezone_offset" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.timezone_offset') }}
                        </label>
                        <input type="text" 
                               id="timezone_offset" 
                               name="timezone_offset" 
                               value="{{ \App\Helpers\SettingsHelper::getTimezoneOffset() }}"
                               pattern="[+-]\d{2}:\d{2}"
                               placeholder="+06:00"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.timezone_offset_from_utc') }}</p>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">{{ __('app.format_preview') }}</h4>
                    <div id="format-preview" class="text-sm text-gray-600 space-y-2">
                        <!-- Preview will be generated by JavaScript -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        {{ __('app.update_format_settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateFormatSelect = document.getElementById('date_format');
    const timeFormatSelect = document.getElementById('time_format');
    const timezoneSelect = document.getElementById('timezone');
    const previewDiv = document.getElementById('format-preview');

    function updatePreview() {
        const dateFormat = dateFormatSelect.value;
        const timeFormat = timeFormatSelect.value;
        const timezone = timezoneSelect.value;
        
        const now = new Date();
        
        // Format date
        let dateStr;
        switch(dateFormat) {
            case 'd/m/Y':
                dateStr = now.toLocaleDateString('en-GB');
                break;
            case 'm/d/Y':
                dateStr = now.toLocaleDateString('en-US');
                break;
            case 'Y-m-d':
                dateStr = now.toISOString().split('T')[0];
                break;
            case 'd-m-Y':
                dateStr = now.toLocaleDateString('en-GB').replace(/\//g, '-');
                break;
            case 'd M Y':
                dateStr = now.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
                break;
            case 'M d, Y':
                dateStr = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                break;
            case 'l, F j, Y':
                dateStr = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                break;
            default:
                dateStr = now.toLocaleDateString();
        }
        
        // Format time
        const timeStr = timeFormat === '12' 
            ? now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })
            : now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
        
        previewDiv.innerHTML = `
            <div class="flex justify-between">
                <span>Current Date:</span>
                <span>${dateStr}</span>
            </div>
            <div class="flex justify-between">
                <span>Current Time:</span>
                <span>${timeStr}</span>
            </div>
            <div class="flex justify-between">
                <span>Timezone:</span>
                <span>${timezone}</span>
            </div>
            <div class="flex justify-between">
                <span>Combined:</span>
                <span>${dateStr} ${timeStr}</span>
            </div>
        `;
    }

    dateFormatSelect.addEventListener('change', updatePreview);
    timeFormatSelect.addEventListener('change', updatePreview);
    timezoneSelect.addEventListener('change', updatePreview);

    // Initial preview
    updatePreview();
});
</script>
@endpush
@endsection
