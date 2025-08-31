@extends('layouts.app')

@section('title', __('app.school_settings'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('app.school_settings') }}</h1>
                <p class="text-gray-600">{{ __('app.manage_school_configuration') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('settings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>{{ __('app.create_new_setting') }}
                </a>
                <form method="POST" action="{{ route('settings.clear-cache') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sync mr-2"></i>{{ __('app.clear_cache') }}
                    </button>
                </form>
                <form method="POST" action="{{ route('settings.reset-defaults') }}" class="inline" onsubmit="return confirm('Are you sure you want to reset all settings to defaults? This action cannot be undone.')">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-undo mr-2"></i>{{ __('app.reset_defaults') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if($groupedSettings->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-cog text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('app.no_settings_found') }}</h3>
            <p class="text-gray-600 mb-4">{{ __('app.no_settings_configured') }}</p>
            <a href="{{ route('settings.reset-defaults') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-magic mr-2"></i>{{ __('app.load_default_settings') }}
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($groupedSettings as $group => $settings)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-{{ $group === 'school_info' ? 'school' : ($group === 'academic' ? 'graduation-cap' : 'cog') }} mr-2"></i>
                            {{ ucwords(str_replace('_', ' ', $group)) }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $settings->count() }} setting{{ $settings->count() !== 1 ? 's' : '' }}
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($settings->take(3) as $setting)
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $setting->label }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($setting->description, 50) }}</p>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <span class="text-sm text-gray-900">
                                            @if($setting->type === 'boolean')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $setting->formatted_value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $setting->formatted_value ? 'Yes' : 'No' }}
                                                </span>
                                            @else
                                                {{ Str::limit($setting->value, 20) }}
                                            @endif
                                        </span>
                                        @if($setting->is_public)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                Public
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($settings->count() > 3)
                                <div class="text-center pt-2">
                                    <span class="text-sm text-gray-500">+{{ $settings->count() - 3 }} more</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('settings.group', $group) }}" class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium text-center block">
                                <i class="fas fa-edit mr-2"></i>Manage Settings
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('settings.group', 'school_info') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-4">
                        <i class="fas fa-school"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">School Information</h4>
                        <p class="text-sm text-gray-600">Update school details and contact information</p>
                    </div>
                </a>
                
                <a href="{{ route('settings.group', 'academic') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="p-2 bg-green-100 text-green-600 rounded-lg mr-4">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Academic Settings</h4>
                        <p class="text-sm text-gray-600">Configure academic year and requirements</p>
                    </div>
                </a>
                
                <a href="{{ route('settings.group', 'system') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="p-2 bg-purple-100 text-purple-600 rounded-lg mr-4">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">System Settings</h4>
                        <p class="text-sm text-gray-600">Manage system configuration and features</p>
                    </div>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
