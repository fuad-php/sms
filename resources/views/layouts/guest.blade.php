<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen bg-gray-50 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo Section -->
        <div class="mb-8">
            <a href="{{ route('home') }}" class="flex items-center">
                <div class="flex-shrink-0">
                    <x-school-logo class="w-20 h-20" />
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ \App\Helpers\SettingsHelper::getSchoolName() }}
                    </h1>
                    <p class="text-sm text-gray-600">{{ __('app.school_management_system') }}</p>
                </div>
            </a>
        </div>

        <!-- Auth Form Container -->
        <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-lg rounded-lg border border-gray-200">
            {{ $slot }}
        </div>

        <!-- Footer Links -->
        <div class="mt-8 text-center">
            <div class="flex items-center justify-center space-x-6 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition-colors">
                    {{ __('app.home') }}
                </a>
                <a href="{{ route('announcements.public') }}" class="hover:text-gray-900 transition-colors">
                    {{ __('app.announcements') }}
                </a>
                <a href="{{ route('contact.index') }}" class="hover:text-gray-900 transition-colors">
                    {{ __('app.contact') }}
                </a>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
