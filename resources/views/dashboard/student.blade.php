@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Student Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Today's Timetable</h2>
        @if(count($todayTimetable) > 0)
            <div class="space-y-4">
                @foreach($todayTimetable as $period)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $period->subject->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-500">{{ $period->teacher->user->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $period->start_time ? $period->start_time->format('H:i') : 'N/A' }} - {{ $period->end_time ? $period->end_time->format('H:i') : 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Room {{ $period->room ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No classes scheduled for today.</p>
        @endif
    </div>

    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Attendance Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $attendanceStats['this_month'] ?? 0 }}%</p>
                <p class="text-sm text-gray-500">This Month</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $attendanceStats['last_month'] ?? 0 }}%</p>
                <p class="text-sm text-gray-500">Last Month</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $attendanceStats['overall'] ?? 0 }}%</p>
                <p class="text-sm text-gray-500">Overall</p>
            </div>
        </div>
    </div>
</div>
@endsection
