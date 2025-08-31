@extends('layouts.app')

@section('title', __('app.result_details'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.result_details') }}</h1>
                    <p class="text-gray-600">{{ __('app.view_exam_result_information') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('results.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        ← {{ __('app.back_to_results') }}
                    </a>
                    @can('update', $result)
                        <a href="{{ route('results.edit', $result) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('app.edit_result') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Result Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Result Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('app.result_information') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Exam Details -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('app.exam_information') }}</h4>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-700">{{ __('app.exam_name') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $result->exam->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-700">{{ __('app.subject') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $result->exam->subject->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-700">{{ __('app.class') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $result->exam->class->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-700">{{ __('app.exam_date') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $result->exam->exam_date->format('M d, Y') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-700">{{ __('app.exam_time') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $result->exam->getTimeRange() }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Student Details -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('app.student_information') }}</h4>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-700">{{ __('app.student_name') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $result->student->user->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-700">{{ __('app.roll_number') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $result->student->roll_number }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-700">{{ __('app.student_id') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $result->student->student_id }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-700">{{ __('app.admission_date') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $result->student->admission_date->format('M d, Y') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Performance Details -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('app.performance_details') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">
                                        @if($result->is_absent)
                                            {{ __('app.absent') }}
                                        @else
                                            {{ $result->marks_obtained }}/{{ $result->exam->total_marks }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ __('app.marks') }}</div>
                                </div>
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">
                                        @if($result->is_absent)
                                            N/A
                                        @else
                                            {{ $result->getPercentage() }}%
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ __('app.percentage') }}</div>
                                </div>
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $result->getGradeBadgeClass() }}">
                                            {{ $result->getGradeCalculated() }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ __('app.grade') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status and Remarks -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('app.result_status') }}</h4>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $result->getStatusBadgeClass() }}">
                                            {{ $result->getStatusText() }}
                                        </span>
                                        @if(!$result->is_absent)
                                            <span class="text-sm text-gray-500">
                                                @if($result->isPassed())
                                                    {{ __('app.above_passing_marks') }} ({{ $result->exam->passing_marks }})
                                                @else
                                                    {{ __('app.below_passing_marks') }} ({{ $result->exam->passing_marks }})
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('app.remarks') }}</h4>
                                    <p class="text-sm text-gray-900">
                                        {{ $result->remarks ?: __('app.no_remarks') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('app.quick_actions') }}</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @can('update', $result)
                            <a href="{{ route('results.edit', $result) }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('app.edit_result') }}
                            </a>
                        @endcan
                        @can('delete', $result)
                            <form method="POST" action="{{ route('results.destroy', $result) }}" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700" onclick="return confirm('{{ __('app.confirm_delete_result') }}')">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    {{ __('app.delete_result') }}
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

                <!-- Result Statistics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('app.result_statistics') }}</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('app.total_marks') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $result->exam->total_marks }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('app.passing_marks') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $result->exam->passing_marks }}</span>
                        </div>
                        @if(!$result->is_absent)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('app.marks_obtained') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $result->marks_obtained }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('app.percentage') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $result->getPercentage() }}%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('app.grade') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $result->getGradeCalculated() }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">{{ __('app.timestamps') }}</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <span class="text-sm text-gray-600">{{ __('app.created_at') }}</span>
                            <div class="text-sm font-medium text-gray-900">{{ $result->created_at->format('M d, Y H:i') }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">{{ __('app.updated_at') }}</span>
                            <div class="text-sm font-medium text-gray-900">{{ $result->updated_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
