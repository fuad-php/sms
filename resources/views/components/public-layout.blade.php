<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-seo.meta />
    <title>{{ \App\Helpers\SettingsHelper::getSchoolName() }} - @yield('title', 'Home')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Top Bar -->
        <div class="bg-gray-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row justify-between items-center py-2 lg:py-3">
                    <!-- Contact Information -->
                    <div class="flex flex-col sm:flex-row items-center space-y-1 sm:space-y-0 sm:space-x-4 lg:space-x-6 text-xs sm:text-sm mb-2 lg:mb-0">
                        @if(\App\Helpers\SettingsHelper::getSchoolEmail())
                            <div class="flex items-center">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1 sm:mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ \App\Helpers\SettingsHelper::getSchoolEmail() }}" class="hover:text-gray-300 transition-colors truncate">
                                    <span class="hidden sm:inline">{{ \App\Helpers\SettingsHelper::getSchoolEmail() }}</span>
                                    <span class="sm:hidden">{{ Str::limit(\App\Helpers\SettingsHelper::getSchoolEmail(), 20) }}</span>
                                </a>
                            </div>
                        @endif
                        @if(\App\Helpers\SettingsHelper::getSchoolPhone())
                            <div class="flex items-center">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1 sm:mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:{{ \App\Helpers\SettingsHelper::getSchoolPhone() }}" class="hover:text-gray-300 transition-colors">
                                    {{ \App\Helpers\SettingsHelper::getSchoolPhone() }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Language Switcher & Auth Buttons -->
                    <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4">
                        <div class="scale-90 sm:scale-100">
                            <x-language-switcher />
                        </div>
                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="bg-white text-gray-800 px-2 sm:px-3 py-1 sm:py-1.5 rounded-md text-xs sm:text-sm font-medium hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors">
                                {{ __('app.dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="text-white hover:text-gray-300 px-2 sm:px-3 py-1 sm:py-1.5 rounded-md text-xs sm:text-sm font-medium transition-colors">
                                {{ __('app.login') }}
                            </a>
                            <a href="{{ route('register') }}" 
                               class="bg-white text-gray-800 px-2 sm:px-3 py-1 sm:py-1.5 rounded-md text-xs sm:text-sm font-medium hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors">
                                {{ __('app.register') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="bg-white shadow-md border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4 md:py-6">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <div class="flex-shrink-0">
                                @if(\App\Helpers\SettingsHelper::hasSchoolLogo())
                                    <img src="{{ \App\Helpers\SettingsHelper::getSchoolLogoUrl() }}" 
                                         alt="{{ \App\Helpers\SettingsHelper::getSchoolName() }} Logo" 
                                         class="h-8 w-auto md:h-10">
                                @else
                                    <div class="h-8 w-8 md:h-10 md:w-10 bg-gray-800 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 md:h-6 md:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-2 md:ml-3">
                                <h1 class="text-lg md:text-xl font-bold text-gray-900 truncate max-w-[200px] md:max-w-none">
                                    {{ \App\Helpers\SettingsHelper::getSchoolName() }}
                                </h1>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ route('home') }}" 
                           class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-gray-900 bg-gray-100' : '' }}">
                            {{ __('app.home') }}
                        </a>
                        <a href="{{ route('announcements.public') }}" 
                           class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('announcements.public*') ? 'text-gray-900 bg-gray-100' : '' }}">
                            {{ __('app.announcements') }}
                        </a>
                        <a href="{{ route('contact.index') }}" 
                           class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('contact.*') ? 'text-gray-900 bg-gray-100' : '' }}">
                            {{ __('app.contact') }}
                        </a>
                    </nav>



                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" 
                                class="mobile-menu-button bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div class="mobile-menu hidden md:hidden">
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200">
                        <a href="{{ route('home') }}" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors {{ request()->routeIs('home') ? 'text-gray-900 bg-gray-100' : '' }}">
                            {{ __('app.home') }}
                        </a>
                        <a href="{{ route('announcements.public') }}" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors {{ request()->routeIs('announcements.public*') ? 'text-gray-900 bg-gray-100' : '' }}">
                            {{ __('app.announcements') }}
                        </a>
                        <a href="{{ route('contact.index') }}" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors {{ request()->routeIs('contact.*') ? 'text-gray-900 bg-gray-100' : '' }}">
                            {{ __('app.contact') }}
                        </a>
                    </div>

                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                    <div class="sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center mb-4">
                            @if(\App\Helpers\SettingsHelper::hasSchoolLogo())
                                <img src="{{ \App\Helpers\SettingsHelper::getSchoolLogoUrl() }}" 
                                     alt="{{ \App\Helpers\SettingsHelper::getSchoolName() }} Logo" 
                                     class="h-8 w-auto mr-3">
                            @else
                                <div class="h-8 w-8 bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                                    </svg>
                                </div>
                            @endif
                            <h3 class="text-lg md:text-xl font-bold">{{ \App\Helpers\SettingsHelper::getSchoolName() }}</h3>
                        </div>
                        <p class="text-gray-400 mb-4 text-sm md:text-base">
                            {{ __('app.nurturing_minds_building_futures') }}
                        </p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-base md:text-lg font-semibold mb-3 md:mb-4">{{ __('app.quick_links') }}</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}#about" class="text-gray-300 hover:text-white transition-colors text-sm md:text-base">{{ __('app.about') }}</a></li>
                            <li><a href="{{ route('home') }}#academics" class="text-gray-300 hover:text-white transition-colors text-sm md:text-base">{{ __('app.academics') }}</a></li>
                            <li><a href="{{ route('home') }}#activities" class="text-gray-300 hover:text-white transition-colors text-sm md:text-base">{{ __('app.activities') }}</a></li>
                            <li><a href="{{ route('announcements.public') }}" class="text-gray-300 hover:text-white transition-colors text-sm md:text-base">{{ __('app.announcements') }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-base md:text-lg font-semibold mb-3 md:mb-4">{{ __('app.resources') }}</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm md:text-base">{{ __('app.parent_portal') }}</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm md:text-base">{{ __('app.student_portal') }}</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm md:text-base">{{ __('app.library') }}</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors text-sm md:text-base">{{ __('app.academic_calendar') }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-base md:text-lg font-semibold mb-3 md:mb-4">{{ __('app.contact_info') }}</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li class="flex items-start">
                                <svg class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm md:text-base">{{ \App\Helpers\SettingsHelper::getSchoolAddress() ?: __('app.address') }}</span>
                            </li>
                            @if(\App\Helpers\SettingsHelper::getSchoolPhone())
                            <li class="flex items-center">
                                <svg class="h-4 w-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:{{ \App\Helpers\SettingsHelper::getSchoolPhone() }}" class="text-sm md:text-base hover:text-white transition-colors">{{ \App\Helpers\SettingsHelper::getSchoolPhone() }}</a>
                            </li>
                            @endif
                            @if(\App\Helpers\SettingsHelper::getSchoolEmail())
                            <li class="flex items-center">
                                <svg class="h-4 w-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ \App\Helpers\SettingsHelper::getSchoolEmail() }}" class="text-sm md:text-base hover:text-white transition-colors">{{ \App\Helpers\SettingsHelper::getSchoolEmail() }}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                    <p class="text-gray-300">&copy; {{ date('Y') }} {{ \App\Helpers\SettingsHelper::getSchoolName() }}. All rights reserved.</p>
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
                    
                    // Update button icon
                    const icon = mobileMenuButton.querySelector('svg');
                    if (mobileMenu.classList.contains('hidden')) {
                        // Show hamburger icon
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
                    } else {
                        // Show close icon
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                    }
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                        const icon = mobileMenuButton.querySelector('svg');
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
                    }
                });

                // Close mobile menu when window is resized to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        mobileMenu.classList.add('hidden');
                        const icon = mobileMenuButton.querySelector('svg');
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
