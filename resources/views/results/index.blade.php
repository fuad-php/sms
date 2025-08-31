@extends('layouts.app')

@section('title', __('app.results_management'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.results_management') }}</h1>
                    <p class="text-gray-600">{{ __('app.manage_exam_results') }}</p>
                </div>
                <div class="flex space-x-3">
                    @can('create', App\Models\ExamResult::class)
                        <a href="{{ route('results.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('app.add_result') }}
                        </a>
                    @endcan
                    @can('bulkImport', App\Models\ExamResult::class)
                        <a href="{{ route('results.bulk-import') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('app.bulk_import') }}
                        </a>
                    @endcan
                    <a href="{{ route('results.statistics') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ __('app.statistics') }}
                    </a>
                    @can('export', App\Models\ExamResult::class)
                        <a href="{{ route('results.export') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('app.export') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('results.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="exam_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.exam') }}</label>
                    <select name="exam_id" id="exam_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('app.all_exams') }}</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }} - {{ $exam->class->name }} ({{ $exam->subject->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.class') }}</label>
                    <select name="class_id" id="class_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('app.all_classes') }}</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.subject') }}</label>
                    <select name="subject_id" id="subject_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('app.all_subjects') }}</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.status') }}</label>
                    <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ __('app.all_statuses') }}</option>
                        <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>{{ __('app.passed') }}</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('app.failed') }}</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>{{ __('app.absent') }}</option>
                    </select>
                </div>
                <div class="lg:col-span-4 flex justify-end space-x-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ __('app.filter') }}
                    </button>
                    <a href="{{ route('results.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md">
                        {{ __('app.clear') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.exam_results') }}</h3>
            </div>
            
            @if($results->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.student') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.exam') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.marks') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.grade') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($results as $result)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ substr($result->student->user->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $result->student->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $result->student->class->name }} - {{ $result->student->roll_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $result->exam->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $result->exam->subject->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $result->exam->exam_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($result->is_absent)
                                            <span class="text-gray-500">{{ __('app.absent') }}</span>
                                        @else
                                            <div class="text-sm text-gray-900">
                                                {{ $result->marks_obtained }}/{{ $result->exam->total_marks }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $result->getPercentage() }}%
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $result->getGradeBadgeClass() }}">
                                            {{ $result->getGradeCalculated() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $result->getStatusBadgeClass() }}">
                                            {{ $result->getStatusText() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('results.show', $result) }}" class="text-blue-600 hover:text-blue-900">
                                                {{ __('app.view') }}
                                            </a>
                                            @can('update', $result)
                                                <a href="{{ route('results.edit', $result) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ __('app.edit') }}
                                                </a>
                                            @endcan
                                            @can('delete', $result)
                                                <form method="POST" action="{{ route('results.destroy', $result) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('app.confirm_delete_result') }}')">
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
                    {{ $results->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('app.no_results_found') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('app.no_results_found_description') }}</p>
                    @can('create', App\Models\ExamResult::class)
                        <div class="mt-6">
                            <a href="{{ route('results.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                {{ __('app.add_result') }}
                            </a>
                        </div>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
