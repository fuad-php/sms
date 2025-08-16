@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Teacher Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Today's Schedule</h2>
        @if(count($todaySchedule) > 0)
            <div class="space-y-4">
                @foreach($todaySchedule as $schedule)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $schedule->subject->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-500">{{ $schedule->class->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $schedule->start_time ? $schedule->start_time->format('H:i') : 'N/A' }} - {{ $schedule->end_time ? $schedule->end_time->format('H:i') : 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Room {{ $schedule->room ?? 'N/A' }}
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
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Upcoming Exams</h2>
        @if(count($upcomingExams) > 0)
            <div class="space-y-4">
                @foreach($upcomingExams as $exam)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $exam->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $exam->class->name ?? 'N/A' }} - {{ $exam->subject->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $exam->exam_date ? $exam->exam_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No upcoming exams.</p>
        @endif
    </div>
</div>
@endsection
