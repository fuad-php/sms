@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Students</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalStudents }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Teachers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalTeachers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Classes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalClasses }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Subjects</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalSubjects }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Rate -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Attendance Rate</h3>
        <div class="flex items-center">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Attendance</span>
                    <span class="text-sm font-medium text-gray-700">{{ $attendanceRate }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $attendanceRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Students -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Students</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentStudents as $student)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">{{ substr($student->user->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->student_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->class->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->admission_date ? $student->admission_date->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upcoming Exams -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Exams</h3>
        <div class="space-y-4">
            @foreach($upcomingExams as $exam)
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">{{ $exam->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $exam->class->name ?? 'N/A' }} - {{ $exam->subject->name ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ $exam->exam_date ? $exam->exam_date->format('M d, Y') : 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $exam->start_time ? $exam->start_time->format('H:i') : 'N/A' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Announcements -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Announcements</h3>
            <a href="{{ route('announcements.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="space-y-4">
            @forelse($recentAnnouncements as $announcement)
            <div class="border-l-4 border-blue-500 pl-4">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900">{{ $announcement->title }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->content, 100) }}</p>
                        <div class="flex items-center mt-2 text-xs text-gray-500">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $announcement->getPriorityBadgeClass() }}">
                                {{ ucfirst($announcement->priority) }}
                            </span>
                            <span class="mx-2">•</span>
                            <span>By {{ $announcement->createdBy->name }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $announcement->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <a href="{{ route('announcements.show', $announcement) }}" class="text-blue-600 hover:text-blue-800 text-xs">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <p class="text-gray-500 text-sm">No recent announcements</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('settings.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
                    <i class="fas fa-cog"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">School Settings</h4>
                    <p class="text-sm text-gray-600">Manage school configuration</p>
                </div>
            </a>
            
            <a href="{{ route('admin.carousel.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="p-2 bg-orange-100 text-orange-600 rounded-lg mr-4">
                    <i class="fas fa-images"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Carousel</h4>
                    <p class="text-sm text-gray-600">Manage homepage slides</p>
                </div>
            </a>
            
            <a href="{{ route('students.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="p-2 bg-green-100 text-green-600 rounded-lg mr-4">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Add Student</h4>
                    <p class="text-sm text-gray-600">Register new student</p>
                </div>
            </a>
            
            <a href="{{ route('announcements.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="p-2 bg-purple-100 text-purple-600 rounded-lg mr-4">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">New Announcement</h4>
                    <p class="text-sm text-gray-600">Create announcement</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
