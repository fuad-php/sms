@extends('layouts.app')

@section('title', __('app.weekly_timetable_overview'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.weekly_timetable_overview') }}</h1>
                    <p class="text-gray-600">{{ __('app.view_weekly_class_schedules') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('timetable.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('app.add_timetable') }}
                    </a>
                    <a href="{{ route('timetable.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        {{ __('app.list_view') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Class Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" class="flex items-center space-x-4">
                <div class="flex-1">
                    <label for="class_filter" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.filter_by_class') }}</label>
                    <select name="class_filter" id="class_filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('app.all_classes') }}</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_filter') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('app.filter') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Weekly Grid -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.weekly_schedule') }}</h3>
            </div>
            
            <!-- Days Header -->
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <div class="bg-gray-50 p-4 text-center">
                    <h4 class="text-sm font-medium text-gray-900">{{ __('app.time') }}</h4>
                </div>
                @foreach($days as $day)
                <div class="bg-gray-50 p-4 text-center">
                    <h4 class="text-sm font-medium text-gray-900 capitalize">{{ $day }}</h4>
                </div>
                @endforeach
            </div>

            <!-- Time Slots -->
            @php
                $timeSlots = [
                    '08:00' => '08:00',
                    '08:45' => '08:45',
                    '09:30' => '09:30',
                    '10:15' => '10:15',
                    '11:00' => '11:00',
                    '11:45' => '11:45',
                    '12:30' => '12:30',
                    '13:15' => '13:15',
                    '14:00' => '14:00',
                    '14:45' => '14:45',
                    '15:30' => '15:30',
                    '16:15' => '16:15',
                ];
            @endphp

            @foreach($timeSlots as $time => $displayTime)
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <!-- Time Column -->
                <div class="bg-white p-3 text-center">
                    <span class="text-sm font-medium text-gray-900">{{ $displayTime }}</span>
                </div>
                
                <!-- Day Columns -->
                @foreach($days as $day)
                <div class="bg-white p-3 min-h-[80px]">
                    @php
                        $dayTimetables = $timetables->get($day, collect());
                        $timeSlotTimetables = $dayTimetables->filter(function($timetable) use ($time) {
                            return $timetable->start_time->format('H:i') === $time;
                        });
                    @endphp
                    
                    @foreach($timeSlotTimetables as $timetable)
                        @if(request('class_filter') == '' || $timetable->class_id == request('class_filter'))
                        <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded text-xs">
                            <div class="font-medium text-blue-900">{{ $timetable->subject->name ?? 'N/A' }}</div>
                            <div class="text-blue-700">{{ $timetable->class->name ?? 'N/A' }}</div>
                            <div class="text-blue-600">{{ $timetable->teacher->name ?? 'N/A' }}</div>
                            @if($timetable->room)
                                <div class="text-blue-500">{{ $timetable->room }}</div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        <!-- Legend -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">{{ __('app.legend') }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-50 border border-blue-200 rounded mr-2"></div>
                    <span class="text-gray-700">{{ __('app.subject') }} - {{ __('app.class') }} - {{ __('app.teacher') }}</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-50 border border-green-200 rounded mr-2"></div>
                    <span class="text-gray-700">{{ __('app.current_period') }}</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-50 border border-yellow-200 rounded mr-2"></div>
                    <span class="text-gray-700">{{ __('app.upcoming') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
