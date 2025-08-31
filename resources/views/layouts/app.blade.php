<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'School Management System'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <div class="flex-shrink-0">
                                <x-application-logo class="block h-10 w-auto fill-current text-gray-800" />
                            </div>
                            <div class="ml-3">
                                <h1 class="text-xl font-bold text-gray-900">
                                    @schoolName
                                </h1>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-8">
                        @auth
                            <a href="{{ route('school.dashboard') }}" 
                               class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('school.dashboard') ? 'text-blue-600' : '' }}">
                                {{ __('app.dashboard') }}
                            </a>
                            <a href="{{ route('students.index') }}" 
                               class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('students.*') ? 'text-blue-600' : '' }}">
                                {{ __('app.students') }}
                            </a>
                            @if(in_array(auth()->user()->role, ['admin', 'teacher']))
                                <a href="{{ route('attendance.index') }}" 
                                   class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('attendance.*') ? 'text-blue-600' : '' }}">
                                    {{ __('app.attendance') }}
                                </a>
                            @endif
                            <a href="{{ route('announcements.index') }}" 
                               class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('announcements.*') ? 'text-blue-600' : '' }}">
                                {{ __('app.announcements') }}
                            </a>
                            <a href="{{ route('timetable.index') }}" 
                               class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('timetable.*') ? 'text-blue-600' : '' }}">
                                {{ __('app.timetable') }}
                            </a>
                            <a href="{{ route('results.index') }}" 
                               class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('results.*') ? 'text-blue-600' : '' }}">
                                {{ __('app.results') }}
                            </a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('settings.index') }}" 
                                   class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.*') ? 'text-blue-600' : '' }}">
                                    {{ __('app.settings') }}
                                </a>
                                <a href="{{ route('admin.contact.index') }}" 
                                   class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.contact.*') ? 'text-blue-600' : '' }}">
                                    {{ __('app.contact_management') }}
                                </a>
                            @endif
                        @endauth
                    </nav>

                    <!-- User Menu & Actions -->
                    <div class="flex items-center space-x-4">
                        <x-language-switcher />
                        
                        @auth
                            <div class="relative">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}" 
                               class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                {{ __('app.profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    {{ __('app.logout') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" 
                               class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                {{ __('app.login') }}
                            </a>
                            <a href="{{ route('register') }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                {{ __('app.register') }}
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" 
                                class="mobile-menu-button bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div class="mobile-menu hidden md:hidden">
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200">
                        @auth
                            <a href="{{ route('school.dashboard') }}" 
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('school.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                                {{ __('app.dashboard') }}
                            </a>
                            <a href="{{ route('students.index') }}" 
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('students.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                {{ __('app.students') }}
                            </a>
                            @if(in_array(auth()->user()->role, ['admin', 'teacher']))
                                <a href="{{ route('attendance.index') }}" 
                                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('attendance.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                    {{ __('app.attendance') }}
                                </a>
                            @endif
                            <a href="{{ route('announcements.index') }}" 
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('announcements.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                {{ __('app.announcements') }}
                            </a>
                            <a href="{{ route('timetable.index') }}" 
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('timetable.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                {{ __('app.timetable') }}
                            </a>
                            <a href="{{ route('results.index') }}" 
                               class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('results.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                {{ __('app.results') }}
                            </a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('settings.index') }}" 
                                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('settings.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                    {{ __('app.settings') }}
                                </a>
                                <a href="{{ route('admin.contact.index') }}" 
                                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('admin.contact.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                    {{ __('app.contact_management') }}
                                </a>
                            @endif
                        @endauth
                    </div>
                    @auth
                        <div class="pt-4 pb-3 border-t border-gray-200">
                            <div class="px-4 py-2">
                                <div class="text-sm text-gray-700 mb-2">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500 mb-3">{{ ucfirst(auth()->user()->role) }}</div>
                                <div class="space-y-2">
                                    <a href="{{ route('profile.edit') }}" 
                                       class="block text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-base font-medium">
                                        {{ __('app.profile') }}
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="block w-full text-left text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-base font-medium">
                                            {{ __('app.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800">{{ session('warning') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">{{ session('info') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name', 'School Management System') }}. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
