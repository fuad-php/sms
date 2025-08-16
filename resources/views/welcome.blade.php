<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-gray-900">School Management System</h1>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('school.dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <!-- Hero Section -->
            <div class="bg-white">
                <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block">Welcome to</span>
                            <span class="block text-blue-600">School Management System</span>
                        </h1>
                        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                            A comprehensive platform for managing students, teachers, classes, attendance, and academic activities.
                        </p>
                        <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                            @auth
                                <div class="rounded-md shadow">
                                    <a href="{{ route('school.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                        Go to Dashboard
                                    </a>
                                </div>
                            @else
                                <div class="rounded-md shadow">
                                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                        Get Started
                                    </a>
                                </div>
                                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                        Sign Up
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-12 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:text-center">
                        <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Features</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Everything you need to manage your school
                        </p>
                    </div>

                    <div class="mt-10">
                        <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Student Management</p>
                                <p class="mt-2 ml-16 text-base text-gray-500">
                                    Manage student records, admissions, and academic progress with ease.
                                </p>
                            </div>

                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Teacher Management</p>
                                <p class="mt-2 ml-16 text-base text-gray-500">
                                    Handle teacher assignments, schedules, and performance tracking.
                                </p>
                            </div>

                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Attendance Tracking</p>
                                <p class="mt-2 ml-16 text-base text-gray-500">
                                    Monitor student attendance and generate comprehensive reports.
                                </p>
                            </div>

                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Exam Management</p>
                                <p class="mt-2 ml-16 text-base text-gray-500">
                                    Create exams, record results, and generate performance analytics.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Demo Accounts Section -->
            @guest
            <div class="bg-blue-50 border-t border-blue-200">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                    <div class="lg:text-center">
                        <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Demo Accounts</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Try the system with demo accounts
                        </p>
                        <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                            Use these demo accounts to explore different user roles and features.
                        </p>
                    </div>

                    <div class="mt-10">
                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Admin</h3>
                                <p class="text-sm text-gray-600 mb-2">Full system access</p>
                                <p class="text-xs text-gray-500">Email: admin@school.com</p>
                                <p class="text-xs text-gray-500">Password: password</p>
                            </div>

                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Teacher</h3>
                                <p class="text-sm text-gray-600 mb-2">Class and student management</p>
                                <p class="text-xs text-gray-500">Email: teacher@school.com</p>
                                <p class="text-xs text-gray-500">Password: password</p>
                            </div>

                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Student</h3>
                                <p class="text-sm text-gray-600 mb-2">View grades and attendance</p>
                                <p class="text-xs text-gray-500">Email: student@school.com</p>
                                <p class="text-xs text-gray-500">Password: password</p>
                            </div>

                            <div class="bg-white rounded-lg shadow p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Parent</h3>
                                <p class="text-sm text-gray-600 mb-2">Monitor child's progress</p>
                                <p class="text-xs text-gray-500">Email: parent@school.com</p>
                                <p class="text-xs text-gray-500">Password: password</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endguest
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-base text-gray-400">&copy; {{ date('Y') }} School Management System. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
