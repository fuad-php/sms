@extends('layouts.app')

@section('title', __('app.timetable_details'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.timetable_details') }}</h1>
                    <p class="text-gray-600">{{ __('app.view_timetable_information') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('timetable.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        ← {{ __('app.back_to_timetable') }}
                    </a>
                    @can('update', $timetable)
                    <a href="{{ route('timetable.edit', $timetable) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        {{ __('app.edit') }}
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Timetable Details -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.timetable_information') }}</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Class Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">{{ __('app.class_information') }}</h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-700">{{ __('app.class') }}:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $timetable->class->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">{{ __('app.subject') }}:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $timetable->subject->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">{{ __('app.teacher') }}:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $timetable->teacher->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">{{ __('app.schedule_information') }}</h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-700">{{ __('app.day') }}:</span>
                                <span class="ml-2 text-sm text-gray-900 capitalize">{{ $timetable->day_of_week }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">{{ __('app.time') }}:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $timetable->getTimeRange() }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">{{ __('app.duration') }}:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $timetable->getDurationFormatted() }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">{{ __('app.room') }}:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $timetable->room ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status and Additional Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">{{ __('app.status') }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $timetable->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $timetable->is_active ? __('app.active') : __('app.inactive') }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">{{ __('app.current_status') }}</h4>
                            @if($timetable->isCurrentPeriod())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ __('app.current_period') }}
                                </span>
                            @elseif($timetable->isUpcoming())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ __('app.upcoming') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ __('app.completed') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">{{ __('app.timestamps') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <span class="text-sm font-medium text-gray-700">{{ __('app.created_at') }}:</span>
                            <span class="ml-2 text-sm text-gray-900">{{ $timetable->created_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">{{ __('app.updated_at') }}:</span>
                            <span class="ml-2 text-sm text-gray-900">{{ $timetable->updated_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-3">
            @can('delete', $timetable)
            <form method="POST" action="{{ route('timetable.destroy', $timetable) }}" class="inline" onsubmit="return confirm('{{ __('app.confirm_delete_timetable') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    {{ __('app.delete') }}
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>
@endsection
