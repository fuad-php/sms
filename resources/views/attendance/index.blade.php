@extends('layouts.app')

@section('title', 'Attendance Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Attendance Management</h1>
        <p class="text-gray-600">View and manage student attendance</p>
    </div>

    <!-- Attendance Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Select Date and Class</h2>
        <form action="{{ route('attendance.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date *</label>
                <input type="date" name="date" id="date" value="{{ request('date', date('Y-m-d')) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="class_id" class="block text-sm font-medium text-gray-700">Class *</label>
                <select name="class_id" id="class_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Class</option>
                    @foreach(\App\Models\SchoolClass::active()->get() as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject (Optional)</label>
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
                    View Attendance
                </button>
            </div>
        </form>
    </div>

    @if(request('date') && request('class_id'))
        <!-- Attendance Overview -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Attendance Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-blue-600">Total Students</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $statistics['total_students'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-green-600">Present</p>
                        <p class="text-2xl font-bold text-green-900">{{ $statistics['present'] ?? 0 }}</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-red-600">Absent</p>
                        <p class="text-2xl font-bold text-red-900">{{ $statistics['absent'] ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-yellow-600">Attendance Rate</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $statistics['attendance_rate'] ?? 0 }}%</p>
                    </div>
                </div>
            </div>

            @if(isset($attendanceData) && count($attendanceData) > 0)
                @if(in_array(auth()->user()->role, ['admin', 'teacher']))
                    <!-- Mark Attendance Form -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Mark Attendance</h3>
                        <form action="{{ route('attendance.mark') }}" method="POST">
                            @csrf
                            <input type="hidden" name="date" value="{{ request('date') }}">
                            <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                            @if(request('subject_id'))
                                <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                            @endif
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marked By</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($attendanceData as $data)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">{{ substr($data['student']->user->name, 0, 2) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $data['student']->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $data['student']->student_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <select name="attendance[{{ $data['student']->id }}][status]" 
                                                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="present" {{ $data['status'] == 'present' ? 'selected' : '' }}>Present</option>
                                                    <option value="absent" {{ $data['status'] == 'absent' ? 'selected' : '' }}>Absent</option>
                                                    <option value="late" {{ $data['status'] == 'late' ? 'selected' : '' }}>Late</option>
                                                    <option value="excused" {{ $data['status'] == 'excused' ? 'selected' : '' }}>Excused</option>
                                                </select>
                                                <input type="hidden" name="attendance[{{ $data['student']->id }}][student_id]" value="{{ $data['student']->id }}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" name="attendance[{{ $data['student']->id }}][remarks]" 
                                                       value="{{ $data['remarks'] ?? '' }}" placeholder="Optional remarks"
                                                       class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $data['marked_by'] ? $data['marked_by']->name : 'Not marked' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" 
                                        class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Save Attendance
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- View Only for Parents and Students -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Records</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marked By</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($attendanceData as $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">{{ substr($data['student']->user->name, 0, 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $data['student']->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $data['student']->student_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($data['status'] == 'present') bg-green-100 text-green-800
                                                @elseif($data['status'] == 'absent') bg-red-100 text-red-800
                                                @elseif($data['status'] == 'late') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($data['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $data['remarks'] ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $data['marked_by'] ? $data['marked_by']->name : 'Not marked' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No students found in this class for the selected date.</p>
                </div>
            @endif
        </div>
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
                    <h3 class="text-sm font-medium text-blue-800">Getting Started</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Please select a date and class above to view and mark attendance for students.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

