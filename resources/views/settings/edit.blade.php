@extends('layouts.app')

@section('title', 'Edit Setting')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Setting</h1>
                <p class="text-gray-600">Modify configuration setting: {{ $setting->label }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('settings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Settings
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-edit mr-2"></i>Setting Information
                </h3>
            </div>
            
            <form method="POST" action="{{ route('settings.update-setting', $setting) }}" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Key (Read-only) -->
                    <div>
                        <label for="key" class="block text-sm font-medium text-gray-900">
                            Setting Key
                        </label>
                        <input type="text" 
                               id="key" 
                               value="{{ $setting->key }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 sm:text-sm"
                               readonly>
                        <p class="mt-1 text-sm text-gray-500">Setting key cannot be changed after creation</p>
                    </div>

                    <!-- Label -->
                    <div>
                        <label for="label" class="block text-sm font-medium text-gray-900">
                            Display Label <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="label" 
                               name="label" 
                               value="{{ old('label', $setting->label) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('label') border-red-500 @enderror"
                               placeholder="e.g., School Name, Maximum Students per Class"
                               required>
                        @error('label')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Human-readable label that will be displayed in the interface</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-900">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-500 @enderror"
                                  placeholder="Brief description of what this setting controls">{{ old('description', $setting->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Optional description to help users understand this setting</p>
                    </div>

                    <!-- Group -->
                    <div>
                        <label for="group" class="block text-sm font-medium text-gray-900">
                            Group <span class="text-red-500">*</span>
                        </label>
                        <select id="group" 
                                name="group" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('group') border-red-500 @enderror"
                                required>
                            <option value="">Select a group</option>
                            <option value="school_info" {{ old('group', $setting->group) === 'school_info' ? 'selected' : '' }}>School Information</option>
                            <option value="academic" {{ old('group', $setting->group) === 'academic' ? 'selected' : '' }}>Academic Settings</option>
                            <option value="system" {{ old('group', $setting->group) === 'system' ? 'selected' : '' }}>System Settings</option>
                            <option value="general" {{ old('group', $setting->group) === 'general' ? 'selected' : '' }}>General</option>
                        </select>
                        @error('group')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Category to organize this setting</p>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-900">
                            Data Type <span class="text-red-500">*</span>
                        </label>
                        <select id="type" 
                                name="type" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('type') border-red-500 @enderror"
                                required>
                            <option value="">Select a type</option>
                            <option value="string" {{ old('type', $setting->type) === 'string' ? 'selected' : '' }}>String (Text)</option>
                            <option value="text" {{ old('type', $setting->type) === 'text' ? 'selected' : '' }}>Text (Long)</option>
                            <option value="number" {{ old('type', $setting->type) === 'number' ? 'selected' : '' }}>Number</option>
                            <option value="boolean" {{ old('type', $setting->type) === 'boolean' ? 'selected' : '' }}>Boolean (Yes/No)</option>
                            <option value="json" {{ old('type', $setting->type) === 'json' ? 'selected' : '' }}>JSON</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">The data type for this setting value</p>
                    </div>

                    <!-- Value -->
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-900">
                            Current Value
                        </label>
                        @switch($setting->type)
                            @case('boolean')
                                <div class="flex items-center mt-1">
                                    <input type="checkbox" 
                                           id="value" 
                                           name="value" 
                                           value="1" 
                                           {{ old('value', $setting->formatted_value) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="value" class="ml-2 block text-sm text-gray-900">
                                        Enable {{ $setting->label }}
                                    </label>
                                </div>
                                @break
                                
                            @case('text')
                                <textarea id="value" 
                                          name="value" 
                                          rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('value') border-red-500 @enderror"
                                          placeholder="Enter the value for this setting">{{ old('value', $setting->value) }}</textarea>
                                @break
                                
                            @case('number')
                                <input type="number" 
                                       id="value" 
                                       name="value" 
                                       value="{{ old('value', $setting->value) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('value') border-red-500 @enderror"
                                       placeholder="Enter the value for this setting">
                                @break
                                
                            @case('json')
                                <textarea id="value" 
                                          name="value" 
                                          rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-mono text-sm @error('value') border-red-500 @enderror"
                                          placeholder='{"key": "value"}'>{{ old('value', $setting->value) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Enter valid JSON format</p>
                                @break
                                
                            @default
                                <input type="text" 
                                       id="value" 
                                       name="value" 
                                       value="{{ old('value', $setting->value) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('value') border-red-500 @enderror"
                                       placeholder="Enter the value for this setting">
                        @endswitch
                        @error('value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Current value for this setting</p>
                    </div>

                    <!-- Public Setting -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_public" 
                               name="is_public" 
                               value="1" 
                               {{ old('is_public', $setting->is_public) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_public" class="ml-2 block text-sm text-gray-900">
                            Public Setting
                        </label>
                    </div>
                    <p class="text-sm text-gray-500">Check this if this setting should be visible to non-admin users</p>

                    <!-- Setting Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Setting Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Created:</span>
                                <span class="text-gray-900">{{ $setting->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Last Updated:</span>
                                <span class="text-gray-900">{{ $setting->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('settings.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>Update Setting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
