@extends('layouts.app')

@section('title', 'Language Test')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('app.contact_us') }}</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-xl font-semibold mb-4">{{ __('app.get_in_touch') }}</h2>
                    <p class="text-gray-600 mb-4">{{ __('app.office_hours') }}: 8:00 AM - 4:00 PM</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-600 mr-3"></i>
                            <span>{{ __('app.school_address') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-600 mr-3"></i>
                            <span>{{ __('app.school_phone') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 mr-3"></i>
                            <span>{{ __('app.school_email') }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-xl font-semibold mb-4">{{ __('app.send_message') }}</h2>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.name') }}</label>
                            <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.email') }}</label>
                            <input type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.subject') }}</label>
                            <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.message') }}</label>
                            <textarea rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('app.submit') }}
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Current Language: {{ app()->getLocale() === 'bn' ? 'বাংলা (Bengali)' : 'English' }}</h3>
                <p class="text-gray-600">Use the language switcher in the navigation to change the language.</p>
            </div>
        </div>
    </div>
</div>
@endsection
