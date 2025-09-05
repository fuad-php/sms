@extends('layouts.app')

@section('title', __('app.student_attendance_report'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('app.student_attendance_report') }}</h1>
        <p class="text-gray-600">{{ __('app.view_detailed_attendance_history_for', ['name' => $student->user->name]) }}</p>
    </div>

    <!-- Student Information -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center">
            @if($student->user->avatar)
                <img src="{{ asset('storage/' . $student->user->avatar) }}" 
                     alt="{{ __('app.student_avatar') }}" 
                     class="w-16 h-16 rounded-full object-cover">
            @else
                <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-2xl font-bold text-gray-600">{{ substr($student->user->name, 0, 2) }}</span>
                </div>
            @endif
            <div class="ml-4">
                <h2 class="text-xl font-semibold text-gray-900">{{ $student->user->name }}</h2>
                <p class="text-gray-600">{{ $student->student_id }} • {{ $student->class->name ?? __('app.no_class') }}</p>
                <p class="text-sm text-gray-500">{{ __('app.roll_number') }}: {{ $student->roll_number ?? __('app.not_available') }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.filter_options') }}</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('app.start_date') }}</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date', date('Y-m-01')) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('app.end_date') }}</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date', date('Y-m-d')) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700">{{ __('app.subject') }}</label>
                <select name="subject_id" id="subject_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">{{ __('app.all_subjects') }}</option>
                    @foreach(\App\Models\Subject::active()->get() as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('app.generate_report') }}
                </button>
            </div>
        </form>
    </div>

    @if(request('start_date') && request('end_date'))
        <!-- Attendance Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $summary['present_count'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ __('app.present') }}</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $summary['absent_count'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ __('app.absent') }}</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $summary['late_count'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">{{ __('app.late') }}</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $summary['attendance_rate'] ?? 0 }}%</div>
                    <div class="text-sm text-gray-500">{{ __('app.attendance_rate') }}</div>
                </div>
            </div>
        </div>

        <!-- Detailed Attendance Records -->
        @if(isset($attendanceRecords) && count($attendanceRecords) > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.attendance_records') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.subject') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.remarks') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.marked_by') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendanceRecords as $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $record->date->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $record->subject ? $record->subject->name : __('app.all_subjects') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'present' => 'bg-green-100 text-green-800',
                                        'absent' => 'bg-red-100 text-red-800',
                                        'late' => 'bg-yellow-100 text-yellow-800',
                                        'excused' => 'bg-blue-100 text-blue-800'
                                    ];
                                    $statusColor = $statusColors[$record->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $record->remarks ?? __('app.not_available') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $record->markedBy ? $record->markedBy->name : __('app.not_available') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center py-8">
                <p class="text-gray-500">{{ __('app.no_attendance_records_found') }}</p>
            </div>
        </div>
        @endif

        <!-- Monthly Chart -->
        @if(isset($monthlyData) && count($monthlyData) > 0)
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.monthly_attendance_trend') }}</h3>
            <div class="h-64 flex items-end space-x-2">
                @foreach($monthlyData as $month)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-blue-200 rounded-t" style="height: {{ $month['percentage'] }}%"></div>
                    <div class="text-xs text-gray-500 mt-2">{{ $month['month'] }}</div>
                    <div class="text-xs font-medium text-gray-900">{{ $month['percentage'] }}%</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @else
        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">{{ __('app.generate_report') }}</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>{{ __('app.generate_attendance_report_instructions') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('students.show', $student->id) }}" 
           class="text-blue-600 hover:text-blue-800 font-medium">
            ← {{ __('app.back_to_student_profile') }}
        </a>
    </div>
</div>
@endsection
