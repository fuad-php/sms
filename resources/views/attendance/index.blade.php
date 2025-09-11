@extends('layouts.app')

@section('title', __('app.attendance'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('attendance.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>{{ __('app.mark_attendance') }}
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Attendance Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('app.select_date_and_class') }}</h2>
        <form action="{{ route('attendance.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">{{ __('app.date') }} *</label>
                <input type="date" name="date" id="date" value="{{ request('date', date('Y-m-d')) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="class_id" class="block text-sm font-medium text-gray-700">{{ __('app.class') }} *</label>
                <select name="class_id" id="class_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">{{ __('app.select_class') }}</option>
                    @foreach(\App\Models\SchoolClass::active()->orderBy('name')->orderBy('section')->get() as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->class_with_section }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700">{{ __('app.subject') }} ({{ __('app.optional') }})</label>
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
                    {{ __('app.view_attendance') }}
                </button>
            </div>
        </form>
    </div>

    @if(request('date') && request('class_id'))
        <!-- Attendance Overview -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('app.attendance_overview') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-blue-600">{{ __('app.total_students') }}</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $statistics['total_students'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-green-600">{{ __('app.present') }}</p>
                        <p class="text-2xl font-bold text-green-900">{{ $statistics['present'] ?? 0 }}</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-red-600">{{ __('app.absent') }}</p>
                        <p class="text-2xl font-bold text-red-900">{{ $statistics['absent'] ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-yellow-600">{{ __('app.attendance_rate') }}</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $statistics['attendance_rate'] ?? 0 }}%</p>
                    </div>
                </div>
            </div>

            @if(isset($attendanceData) && count($attendanceData) > 0)
                @if(in_array(auth()->user()->role, ['admin', 'teacher']))
                    <!-- Mark Attendance Form -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.mark_attendance') }}</h3>
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.student') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.remarks') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.marked_by') }}</th>
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
                                                    <option value="present" {{ $data['status'] == 'present' ? 'selected' : '' }}>{{ __('app.present') }}</option>
                                                    <option value="absent" {{ $data['status'] == 'absent' ? 'selected' : '' }}>{{ __('app.absent') }}</option>
                                                    <option value="late" {{ $data['status'] == 'late' ? 'selected' : '' }}>{{ __('app.late') }}</option>
                                                    <option value="excused" {{ $data['status'] == 'excused' ? 'selected' : '' }}>{{ __('app.excused') }}</option>
                                                </select>
                                                <input type="hidden" name="attendance[{{ $data['student']->id }}][student_id]" value="{{ $data['student']->id }}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" name="attendance[{{ $data['student']->id }}][remarks]" 
                                                       value="{{ $data['remarks'] ?? '' }}" placeholder="{{ __('app.optional_remarks') }}"
                                                       class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $data['marked_by'] ? $data['marked_by']->name : __('app.not_marked') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" 
                                        class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    {{ __('app.save_attendance') }}
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- View Only for Parents and Students -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.attendance_records') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.student') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.remarks') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.marked_by') }}</th>
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
                                            {{ $data['marked_by'] ? $data['marked_by']->name : __('app.not_marked') }}
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
                    <p class="text-gray-500">{{ __('app.no_students_found_for_date') }}</p>
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
                    <h3 class="text-sm font-medium text-blue-800">{{ __('app.getting_started') }}</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>{{ __('app.select_date_class_attendance_instructions') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
@endsection

