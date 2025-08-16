<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Maintenance - @schoolName</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="max-w-md mx-auto text-center">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-gray-400 mb-6">
                    <i class="fas fa-tools text-6xl"></i>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-4">
                    System Maintenance
                </h1>
                
                <p class="text-gray-600 mb-6">
                    We're currently performing scheduled maintenance to improve our services. 
                    Please check back soon.
                </p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <span class="text-sm text-blue-700">
                            Expected completion time: {{ now()->addHours(2)->format('g:i A') }}
                        </span>
                    </div>
                </div>
                
                <div class="text-sm text-gray-500">
                    <p>If you need immediate assistance, please contact us:</p>
                    @if(\App\Helpers\SettingsHelper::getSchoolEmail())
                        <p class="mt-2">
                            <i class="fas fa-envelope mr-1"></i>
                            {{ \App\Helpers\SettingsHelper::getSchoolEmail() }}
                        </p>
                    @endif
                    @if(\App\Helpers\SettingsHelper::getSchoolPhone())
                        <p class="mt-1">
                            <i class="fas fa-phone mr-1"></i>
                            {{ \App\Helpers\SettingsHelper::getSchoolPhone() }}
                        </p>
                    @endif
                </div>
                
                <div class="mt-8">
                    <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sync mr-2"></i>Try Again
                    </button>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} @schoolName. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
