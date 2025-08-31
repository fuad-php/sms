@extends('layouts.app')

@section('title', 'Class Attendance Report')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Class Attendance Report</h1>
        <p class="text-gray-600">View attendance summary for {{ $class->name }}</p>
    </div>

    <!-- Class Information -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Class Name</label>
                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $class->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Total Students</label>
                <p class="mt-1 text-lg font-semibold text-blue-600">{{ $totalStudents }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Class Teacher</label>
                <p class="mt-1 text-lg font-semibold text-gray-900">
                    {{ $class->teacher ? $class->teacher->user->name : 'Not Assigned' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Options</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date', date('Y-m-01')) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date', date('Y-m-d')) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                <select name="subject_id" id="subject_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Subjects</option>
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
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    @if(request('start_date') && request('end_date'))
        <!-- Overall Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $summary['total_present'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total Present</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $summary['total_absent'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total Absent</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $summary['average_attendance'] ?? 0 }}%</div>
                    <div class="text-sm text-gray-500">Average Attendance</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $summary['total_days'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total Days</div>
                </div>
            </div>
        </div>

        <!-- Student-wise Attendance Summary -->
        @if(isset($studentAttendance) && count($studentAttendance) > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.student_attendance_summary') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($studentAttendance as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($student['user']->avatar)
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ asset('storage/' . $student['user']->avatar) }}" 
                                                 alt="{{ $student['user']->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">{{ substr($student['user']->name, 0, 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $student['user']->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $student['student_id'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="text-green-600 font-medium">{{ $student['present_count'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="text-red-600 font-medium">{{ $student['absent_count'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="text-yellow-600 font-medium">{{ $student['late_count'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $rate = $student['attendance_rate'];
                                    $colorClass = $rate >= 90 ? 'text-green-600' : ($rate >= 75 ? 'text-yellow-600' : 'text-red-600');
                                @endphp
                                <span class="font-medium {{ $colorClass }}">{{ $rate }}%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('attendance.student-report', $student['id']) }}" 
                                   class="text-blue-600 hover:text-blue-900">View Details</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Daily Attendance Trend -->
        @if(isset($dailyTrends) && count($dailyTrends) > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daily Attendance Trend</h3>
            <div class="h-64 flex items-end space-x-2">
                @foreach($dailyTrends as $trend)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-blue-200 rounded-t" style="height: {{ $trend['percentage'] }}%"></div>
                    <div class="text-xs text-gray-500 mt-2">{{ $trend['date'] }}</div>
                    <div class="text-xs font-medium text-gray-900">{{ $trend['percentage'] }}%</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Performers and Concerns -->
        @if(isset($topPerformers) || isset($concerns))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            @if(isset($topPerformers) && count($topPerformers) > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Top Performers</h3>
                <div class="space-y-3">
                    @foreach($topPerformers as $index => $student)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-lg font-bold text-yellow-500 mr-3">{{ $index + 1 }}</span>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $student['user']->name }}</div>
                                <div class="text-xs text-gray-500">{{ $student['attendance_rate'] }}% attendance</div>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-green-600">{{ $student['attendance_rate'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(isset($concerns) && count($concerns) > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Students Needing Attention</h3>
                <div class="space-y-3">
                    @foreach($concerns as $student)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $student['user']->name }}</div>
                                <div class="text-xs text-gray-500">{{ $student['attendance_rate'] }}% attendance</div>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-red-600">{{ $student['attendance_rate'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
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
                    <h3 class="text-sm font-medium text-blue-800">Generate Report</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Please select a date range and optionally a subject to generate the class attendance report.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('attendance.index') }}" 
           class="text-blue-600 hover:text-blue-800 font-medium">
            ← Back to Attendance Management
        </a>
    </div>
</div>
@endsection
