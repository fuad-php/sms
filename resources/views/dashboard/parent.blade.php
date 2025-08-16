@extends('layouts.app')

@section('title', 'Parent Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Parent Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Family Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $totalChildren ?? 0 }}</p>
                <p class="text-sm text-gray-500">Total Children</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $averageAttendance ?? 0 }}%</p>
                <p class="text-sm text-gray-500">Average Attendance</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $averageGrades ?? 0 }}%</p>
                <p class="text-sm text-gray-500">Average Grades</p>
            </div>
        </div>

        @if(count($childrenData) > 0)
            <div class="space-y-4">
                @foreach($childrenData as $childData)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $childData['student']->user->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-500">{{ $childData['student']->class->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">Attendance: {{ $childData['attendance_percentage'] ?? 0 }}%</p>
                            <p class="text-sm text-gray-500">Grade Average: {{ $childData['grade_average'] ?? 0 }}%</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No children found.</p>
        @endif
    </div>
</div>
@endsection
