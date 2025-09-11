@extends('layouts.app')

@section('title', __('app.teacher_scheduling'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('teacher-scheduling.workload-analytics') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-chart-bar mr-2"></i>{{ __('app.workload_analytics') }}
            </a>
            @can('admin')
            <a href="{{ route('teacher-scheduling.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>{{ __('app.add_schedule') }}
            </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('app.total_schedules') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_schedules'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('app.active_schedules') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_schedules'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('app.teachers_with_schedules') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['teachers_with_schedules'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('app.classes_with_schedules') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['classes_with_schedules'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.filter_by_teacher') }}</label>
                    <select name="teacher_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('app.all_teachers') }}</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->user_id }}" {{ request('teacher_id') == $teacher->user_id ? 'selected' : '' }}>
                                {{ $teacher->getFullNameAttribute() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.filter_by_day') }}</label>
                    <select name="day_of_week" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('app.all_days') }}</option>
                        <option value="monday" {{ request('day_of_week') == 'monday' ? 'selected' : '' }}>{{ __('app.monday') }}</option>
                        <option value="tuesday" {{ request('day_of_week') == 'tuesday' ? 'selected' : '' }}>{{ __('app.tuesday') }}</option>
                        <option value="wednesday" {{ request('day_of_week') == 'wednesday' ? 'selected' : '' }}>{{ __('app.wednesday') }}</option>
                        <option value="thursday" {{ request('day_of_week') == 'thursday' ? 'selected' : '' }}>{{ __('app.thursday') }}</option>
                        <option value="friday" {{ request('day_of_week') == 'friday' ? 'selected' : '' }}>{{ __('app.friday') }}</option>
                        <option value="saturday" {{ request('day_of_week') == 'saturday' ? 'selected' : '' }}>{{ __('app.saturday') }}</option>
                        <option value="sunday" {{ request('day_of_week') == 'sunday' ? 'selected' : '' }}>{{ __('app.sunday') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.filter_by_type') }}</label>
                    <select name="schedule_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('app.all_types') }}</option>
                        <option value="regular" {{ request('schedule_type') == 'regular' ? 'selected' : '' }}>{{ __('app.regular') }}</option>
                        <option value="substitute" {{ request('schedule_type') == 'substitute' ? 'selected' : '' }}>{{ __('app.substitute') }}</option>
                        <option value="extra" {{ request('schedule_type') == 'extra' ? 'selected' : '' }}>{{ __('app.extra') }}</option>
                        <option value="remedial" {{ request('schedule_type') == 'remedial' ? 'selected' : '' }}>{{ __('app.remedial') }}</option>
                        <option value="exam_prep" {{ request('schedule_type') == 'exam_prep' ? 'selected' : '' }}>{{ __('app.exam_prep') }}</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        {{ __('app.filter') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('app.schedules') }}</h3>
        </div>

        @if($schedules->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.teacher') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.class') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.subject') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.day_of_week') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.time_range') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.room_number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.schedule_type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.schedule_status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($schedules as $schedule)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->teacher->getFullNameAttribute() }}</div>
                                    <div class="text-sm text-gray-500">{{ $schedule->teacher->department ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $schedule->class->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $schedule->subject->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $schedule->getDayName() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $schedule->getTimeRange() }}</div>
                                    <div class="text-sm text-gray-500">{{ $schedule->getDurationFormatted() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $schedule->room_number ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->getTypeBadgeClass() }}">
                                        {{ __('app.' . $schedule->schedule_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->getStatusBadgeClass() }}">
                                        {{ $schedule->getStatusText() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('teacher-scheduling.teacher-schedule', $schedule->teacher) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            {{ __('app.view') }}
                                        </a>
                                        @can('admin')
                                        <a href="{{ route('teacher-scheduling.edit', $schedule) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            {{ __('app.edit') }}
                                        </a>
                                        <form method="POST" action="{{ route('teacher-scheduling.toggle-status', $schedule) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                {{ $schedule->is_active ? __('app.deactivate') : __('app.activate') }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('teacher-scheduling.destroy', $schedule) }}" class="inline" 
                                              onsubmit="return confirm('{{ __('app.confirm_delete_schedule') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                {{ __('app.delete') }}
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $schedules->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('app.no_schedules_found') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('app.get_started_by_creating_schedule') }}</p>
                @can('admin')
                <div class="mt-6">
                    <a href="{{ route('teacher-scheduling.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('app.add_schedule') }}
                    </a>
                </div>
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection
