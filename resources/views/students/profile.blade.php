@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600">View and manage your student profile</p>
    </div>

    @php
        $student = auth()->user()->student;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    @if($student->user->avatar)
                        <img src="{{ asset('storage/' . $student->user->avatar) }}" 
                             alt="Profile Picture" 
                             class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    @else
                        <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-gray-300 flex items-center justify-center">
                            <span class="text-4xl font-bold text-gray-600">{{ substr($student->user->name, 0, 2) }}</span>
                        </div>
                    @endif
                    
                    <h2 class="text-xl font-semibold text-gray-900">{{ $student->user->name }}</h2>
                    <p class="text-gray-600">{{ $student->student_id }}</p>
                    <p class="text-sm text-gray-500">{{ $student->class->name ?? 'No Class Assigned' }}</p>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center text-sm">
                        <span class="text-gray-500 w-20">Roll No:</span>
                        <span class="font-medium">{{ $student->roll_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="text-gray-500 w-20">Gender:</span>
                        <span class="font-medium">{{ ucfirst($student->user->gender ?? 'N/A') }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="text-gray-500 w-20">Status:</span>
                        <span class="font-medium {{ $student->user->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $student->user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('profile.edit') }}" 
                       class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200 text-center block">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Full Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $student->user->date_of_birth ? $student->user->date_of_birth->format('F j, Y') : 'N/A' }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->user->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Student ID</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->student_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Roll Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->roll_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Class</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->class->name ?? 'No Class Assigned' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Admission Date</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $student->admission_date ? $student->admission_date->format('F j, Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Guardian Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Guardian Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Parent/Guardian Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->parent_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Parent/Guardian Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->parent_phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $student->getAttendancePercentage() ?? 0 }}%</div>
                        <div class="text-sm text-gray-500">Attendance Rate</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $student->getCurrentGradeAverage() ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">Grade Average</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $student->exams()->count() }}</div>
                        <div class="text-sm text-gray-500">Total Exams</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
