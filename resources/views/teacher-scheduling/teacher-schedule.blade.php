@extends('layouts.app')

@section('title', __('app.teacher_schedule'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $teacher->getFullNameAttribute() }}</h1>
                <p class="mt-2 text-gray-600">{{ __('app.teacher_schedule') }} - {{ $teacher->department ?? 'N/A' }}</p>
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

    <!-- Workload Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('app.total_weekly_hours') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $workloadStats['formatted_weekly'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('app.average_daily_hours') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $workloadStats['formatted_daily_avg'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('app.schedule_count') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $weeklySchedule->flatten()->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('app.weekly_schedule') }}</h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
                @php
                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    $dayNames = [
                        'monday' => __('app.monday'),
                        'tuesday' => __('app.tuesday'),
                        'wednesday' => __('app.wednesday'),
                        'thursday' => __('app.thursday'),
                        'friday' => __('app.friday'),
                        'saturday' => __('app.saturday'),
                        'sunday' => __('app.sunday')
                    ];
                @endphp

                @foreach($days as $day)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">{{ $dayNames[$day] }}</h4>
                        
                        @if(isset($weeklySchedule[$day]) && $weeklySchedule[$day]->count() > 0)
                            <div class="space-y-2">
                                @foreach($weeklySchedule[$day] as $schedule)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $schedule->class->name }}</div>
                                        <div class="text-xs text-gray-600">{{ $schedule->subject->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $schedule->getTimeRange() }}
                                            @if($schedule->room_number)
                                                - {{ $schedule->room_number }}
                                            @endif
                                        </div>
                                        <div class="mt-1">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->getTypeBadgeClass() }}">
                                                {{ __('app.' . $schedule->schedule_type) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-500 italic">{{ __('app.no_schedules') }}</div>
                        @endif

                        <!-- Day Workload Summary -->
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="text-xs text-gray-600">
                                {{ __('app.total') }}: {{ $workloadDistribution[$day]['formatted'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Workload Distribution Chart -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('app.workload_distribution') }}</h3>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                @foreach($workloadDistribution as $day => $data)
                    <div class="flex items-center">
                        <div class="w-20 text-sm font-medium text-gray-700">
                            {{ $dayNames[$day] }}
                        </div>
                        <div class="flex-1 mx-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $maxHours = max(array_column($workloadDistribution, 'hours'));
                                    $percentage = $maxHours > 0 ? ($data['hours'] / $maxHours) * 100 : 0;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <div class="w-16 text-sm text-gray-600 text-right">
                            {{ $data['formatted'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
