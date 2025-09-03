@extends('layouts.app')

@section('title', __('app.parent_details'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $parent->user->name }}</h1>
                    <p class="text-gray-600">{{ __('app.parent_details') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('parents.edit', $parent) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('app.edit') }}
                    </a>
                    <a href="{{ route('parents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('app.back_to_parents') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('app.personal_information') }}</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('app.full_name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $parent->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('app.email') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $parent->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('app.phone') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $parent->user->phone ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('app.occupation') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $parent->occupation ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('app.workplace') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $parent->workplace ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('app.income_range') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($parent->income_range)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($parent->income_range) }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        @if($parent->notes)
                            <div class="mt-6">
                                <dt class="text-sm font-medium text-gray-500">{{ __('app.notes') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $parent->notes }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Children Information -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('app.children') }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $parent->students->count() }} {{ __('app.children') }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($parent->students->count() > 0)
                            <div class="space-y-4">
                                @foreach($parent->students as $student)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $student->class->name ?? 'N/A' }} - {{ $student->class->section ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $student->pivot->relationship }}
                                            </span>
                                            <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <p class="mt-2 text-sm">{{ __('app.no_children_assigned') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Children Performance -->
                @if($parent->students->count() > 0)
                    <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('app.children_performance') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Attendance Overview -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-4">{{ __('app.attendance_overview') }}</h4>
                                    <div class="space-y-3">
                                        @foreach($childrenAttendance as $studentId => $data)
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">{{ $data['student']->name }}</span>
                                                <span class="text-sm font-medium text-gray-900">{{ $data['percentage'] }}%</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Grades Overview -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-4">{{ __('app.grades_overview') }}</h4>
                                    <div class="space-y-3">
                                        @foreach($childrenGrades as $studentId => $data)
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">{{ $data['student']->name }}</span>
                                                <span class="text-sm font-medium text-gray-900">{{ $data['average'] ?? 'N/A' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('app.status') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ __('app.account_status') }}</span>
                            @if($parent->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('app.active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('app.inactive') }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">{{ __('app.member_since') }}</span>
                            <span class="text-sm text-gray-900">{{ $parent->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('app.quick_actions') }}</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <form method="POST" action="{{ route('parents.toggle-status', $parent) }}" class="inline w-full">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-{{ $parent->is_active ? 'red' : 'green' }}-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-{{ $parent->is_active ? 'red' : 'green' }}-700 focus:bg-{{ $parent->is_active ? 'red' : 'green' }}-700 active:bg-{{ $parent->is_active ? 'red' : 'green' }}-900 focus:outline-none focus:ring-2 focus:ring-{{ $parent->is_active ? 'red' : 'green' }}-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                @if($parent->is_active)
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                    </svg>
                                    {{ __('app.deactivate') }}
                                @else
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('app.activate') }}
                                @endif
                            </button>
                        </form>

                        <a href="{{ route('parents.edit', $parent) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('app.edit_parent') }}
                        </a>

                        <form method="POST" action="{{ route('parents.destroy', $parent) }}" class="inline w-full" onsubmit="return confirm('{{ __('app.are_you_sure_delete_parent') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                {{ __('app.delete_parent') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
