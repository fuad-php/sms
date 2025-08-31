<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@schoolName - @yield('title', 'Home')</title>

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
                        <a href="{{ route('home') }}" 
                           class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'text-blue-600' : '' }}">
                            {{ __('app.home') }}
                        </a>
                        <a href="{{ route('announcements.public') }}" 
                           class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('announcements.public*') ? 'text-blue-600' : '' }}">
                            {{ __('app.announcements') }}
                        </a>
                        <a href="{{ route('contact.index') }}" 
                           class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('contact.*') ? 'text-blue-600' : '' }}">
                            {{ __('app.contact') }}
                        </a>
                    </nav>

                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-4">
                        <x-language-switcher />
                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                {{ __('app.dashboard') }}
                            </a>
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
                        <a href="{{ route('home') }}" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">
                            {{ __('app.home') }}
                        </a>
                        <a href="{{ route('announcements.public') }}" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('announcements.public*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            {{ __('app.announcements') }}
                        </a>
                        <a href="{{ route('contact.index') }}" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('contact.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            {{ __('app.contact') }}
                        </a>
                    </div>
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        @auth
                            <div class="px-4 py-2">
                                <a href="{{ route('dashboard') }}" 
                                   class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                                    {{ __('app.dashboard') }}
                                </a>
                            </div>
                        @else
                            <div class="px-4 space-y-2">
                                <a href="{{ route('login') }}" 
                                   class="block text-center text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-base font-medium">
                                    {{ __('app.login') }}
                                </a>
                                <a href="{{ route('register') }}" 
                                   class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                                    {{ __('app.register') }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- School Info -->
                    <div class="col-span-1 md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ config('app.name', 'School Management System') }}</h3>
                        <p class="text-gray-600 mb-4">
                            Providing quality education and fostering academic excellence in a supportive learning environment.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <span class="sr-only">Facebook</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <span class="sr-only">Twitter</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('announcements.public') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors">
                                    Announcements
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact.index') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors">
                                    Contact
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Contact</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li>
                                <span class="block">Email: info@school.com</span>
                            </li>
                            <li>
                                <span class="block">Phone: +1 (555) 123-4567</span>
                            </li>
                            <li>
                                <span class="block">Address: 123 School St, City, State</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-200">
                    <p class="text-center text-gray-500 text-sm">
                        &copy; {{ date('Y') }} {{ config('app.name', 'School Management System') }}. All rights reserved.
                    </p>
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
