@extends('layouts.app')

@section('title', $groupName . ' Settings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $groupName }} Settings</h1>
                <p class="text-gray-600">Manage {{ strtolower($groupName) }} configuration</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('settings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Settings
                </a>
                <a href="{{ route('settings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Add Setting
                </a>
            </div>
        </div>
    </div>

    @if($settings->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-cog text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Settings Found</h3>
            <p class="text-gray-600 mb-4">No settings have been configured for this group yet.</p>
            <a href="{{ route('settings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Add First Setting
            </a>
        </div>
    @else
        <form method="POST" action="{{ route('settings.update-group', $group) }}">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-{{ $group === 'school_info' ? 'school' : ($group === 'academic' ? 'graduation-cap' : 'cog') }} mr-2"></i>
                        {{ $groupName }} Configuration
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Update {{ strtolower($groupName) }} settings below
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($settings as $setting)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <label for="setting_{{ $setting->id }}" class="block text-sm font-medium text-gray-900">
                                            {{ $setting->label }}
                                            @if($setting->is_public)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                    Public
                                                </span>
                                            @endif
                                        </label>
                                        @if($setting->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $setting->description }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex space-x-2">
                                        <a href="{{ route('settings.edit', $setting) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('settings.destroy', $setting) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="max-w-md">
                                    @switch($setting->type)
                                        @case('boolean')
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       id="setting_{{ $setting->id }}" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       value="1" 
                                                       {{ $setting->formatted_value ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <label for="setting_{{ $setting->id }}" class="ml-2 block text-sm text-gray-900">
                                                    Enable {{ $setting->label }}
                                                </label>
                                            </div>
                                            @break
                                            
                                        @case('text')
                                            <textarea id="setting_{{ $setting->id }}" 
                                                      name="settings[{{ $setting->key }}]" 
                                                      rows="3"
                                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                      placeholder="Enter {{ strtolower($setting->label) }}">{{ $setting->value }}</textarea>
                                            @break
                                            
                                        @case('number')
                                            <input type="number" 
                                                   id="setting_{{ $setting->id }}" 
                                                   name="settings[{{ $setting->key }}]" 
                                                   value="{{ $setting->value }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                   placeholder="Enter {{ strtolower($setting->label) }}">
                                            @break
                                            
                                        @case('json')
                                            <textarea id="setting_{{ $setting->id }}" 
                                                      name="settings[{{ $setting->key }}]" 
                                                      rows="3"
                                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-mono text-sm"
                                                      placeholder='{"key": "value"}'>{{ $setting->value }}</textarea>
                                            <p class="text-xs text-gray-500 mt-1">Enter valid JSON format</p>
                                            @break
                                            
                                        @default
                                            <input type="text" 
                                                   id="setting_{{ $setting->id }}" 
                                                   name="settings[{{ $setting->key }}]" 
                                                   value="{{ $setting->value }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                   placeholder="Enter {{ strtolower($setting->label) }}">
                                    @endswitch
                                </div>
                                
                                <div class="mt-2 flex items-center text-xs text-gray-500">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($setting->type) }}
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span>Key: {{ $setting->key }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('settings.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
