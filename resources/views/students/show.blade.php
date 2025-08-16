@extends('layouts.app')

@section('title', 'Student Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Student Details</h1>
        <p class="text-gray-600">View student information</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Student Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $student->user->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Student ID</label>
                <p class="mt-1 text-sm text-gray-900">{{ $student->student_id }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Class</label>
                <p class="mt-1 text-sm text-gray-900">{{ $student->class->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ $student->user->email }}</p>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <a href="{{ route('students.edit', $student->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Edit Student
            </a>
            <a href="{{ route('students.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection
