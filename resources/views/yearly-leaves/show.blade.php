@extends('layouts.app')

@section('title', __('app.view_yearly_leave_setting'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('yearly-leaves.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('app.back_to_list') }}
            </a>
            <a href="{{ route('yearly-leaves.edit', $yearlyLeave) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors ml-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg">

        <div class="px-6 py-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="font-medium text-gray-700">Title:</div>
                            <div class="sm:col-span-2">
                                <h5 class="text-lg font-semibold text-gray-900">{{ $yearlyLeave->title }}</h5>
                            </div>
                        </div>

                        @if($yearlyLeave->description)
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="font-medium text-gray-700">Description:</div>
                            <div class="sm:col-span-2">
                                <p class="text-gray-900">{{ $yearlyLeave->description }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="font-medium text-gray-700">Type:</div>
                            <div class="sm:col-span-2">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $yearlyLeave->type === 'holiday' ? 'bg-green-100 text-green-800' : ($yearlyLeave->type === 'vacation' ? 'bg-blue-100 text-blue-800' : ($yearlyLeave->type === 'exam_period' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $yearlyLeave->type_label }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="font-medium text-gray-700">Date Range:</div>
                            <div class="sm:col-span-2">
                                <h6 class="text-lg font-semibold text-gray-900">{{ $yearlyLeave->formatted_date_range }}</h6>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="font-medium text-gray-700">Duration:</div>
                            <div class="sm:col-span-2">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $yearlyLeave->duration }} {{ $yearlyLeave->duration == 1 ? 'day' : 'days' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="font-medium text-gray-700">Year:</div>
                            <div class="sm:col-span-2">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $yearlyLeave->year }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="font-medium text-gray-700">Status:</div>
                            <div class="sm:col-span-2">
                                <div class="flex flex-wrap gap-2">
                                    @if($yearlyLeave->isCurrent())
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">{{ __('app.current') }}</span>
                                    @elseif($yearlyLeave->isUpcoming())
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">{{ __('app.upcoming') }}</span>
                                    @else
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">{{ __('app.past') }}</span>
                                    @endif
                                    
                                    @if($yearlyLeave->is_active)
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">{{ __('app.active') }}</span>
                                    @else
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">{{ __('app.inactive') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h6 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Quick Actions
                        </h6>
                        <div class="space-y-3">
                            <a href="{{ route('yearly-leaves.edit', $yearlyLeave) }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('app.edit_this_leave') }}
                            </a>
                            
                            <form action="{{ route('yearly-leaves.toggle-status', $yearlyLeave) }}" 
                                  method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-{{ $yearlyLeave->is_active ? 'yellow' : 'green' }}-600 hover:bg-{{ $yearlyLeave->is_active ? 'yellow' : 'green' }}-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors"
                                        onclick="return confirm('Are you sure?')">
                                    @if($yearlyLeave->is_active)
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                    {{ $yearlyLeave->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            
                            <form action="{{ route('yearly-leaves.destroy', $yearlyLeave) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this leave setting?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    {{ __('app.delete_this_leave') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6 mt-4">
                        <h6 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Timeline
                        </h6>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-3 h-3 bg-blue-600 rounded-full mt-2"></div>
                                <div class="ml-4">
                                    <h6 class="text-sm font-semibold text-gray-900">Created</h6>
                                    <p class="text-sm text-gray-600">{{ $yearlyLeave->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                            
                            @if($yearlyLeave->updated_at != $yearlyLeave->created_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                                <div class="ml-4">
                                    <h6 class="text-sm font-semibold text-gray-900">Last Updated</h6>
                                    <p class="text-sm text-gray-600">{{ $yearlyLeave->updated_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-3 h-3 bg-{{ $yearlyLeave->isUpcoming() ? 'yellow' : ($yearlyLeave->isCurrent() ? 'green' : 'gray') }}-600 rounded-full mt-2"></div>
                                <div class="ml-4">
                                    <h6 class="text-sm font-semibold text-gray-900">Leave Period</h6>
                                    <p class="text-sm text-gray-600">{{ $yearlyLeave->formatted_date_range }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
