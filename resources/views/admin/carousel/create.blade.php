@extends('layouts.app')

@section('title', __('app.add_slide'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('admin.carousel.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('app.back') }}
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-plus mr-2"></i>{{ __('app.slide_information') }}
                </h3>
            </div>
            
            <form method="POST" action="{{ route('admin.carousel.store') }}" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <div class="space-y-6">
                    <!-- English Fields -->
                    <div class="border-b pb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('app.english') }}</h4>
                        <div>
                            <label for="title_en" class="block text-sm font-medium text-gray-900">
                                {{ __('app.slide_title_en') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('title_en') border-red-500 @enderror" required>
                            @error('title_en')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-4">
                            <label for="subtitle_en" class="block text-sm font-medium text-gray-900">{{ __('app.slide_subtitle_en') }}</label>
                            <input type="text" id="subtitle_en" name="subtitle_en" value="{{ old('subtitle_en') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('subtitle_en') border-red-500 @enderror">
                            @error('subtitle_en')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-4">
                            <label for="description_en" class="block text-sm font-medium text-gray-900">{{ __('app.slide_description_en') }}</label>
                            <textarea id="description_en" name="description_en" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description_en') border-red-500 @enderror">{{ old('description_en') }}</textarea>
                            @error('description_en')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-4">
                            <label for="button_text_en" class="block text-sm font-medium text-gray-900">{{ __('app.button_text_en') }}</label>
                            <input type="text" id="button_text_en" name="button_text_en" value="{{ old('button_text_en') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('button_text_en') border-red-500 @enderror"
                                   placeholder="e.g., {{ __('app.learn_more') }}, {{ __('app.get_started') }}">
                            @error('button_text_en')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Bangla Fields -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('app.bangla') }}</h4>
                        <div>
                            <label for="title_bn" class="block text-sm font-medium text-gray-900">{{ __('app.slide_title_bn') }}</label>
                            <input type="text" id="title_bn" name="title_bn" value="{{ old('title_bn') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('title_bn') border-red-500 @enderror">
                            @error('title_bn')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-4">
                            <label for="subtitle_bn" class="block text-sm font-medium text-gray-900">{{ __('app.slide_subtitle_bn') }}</label>
                            <input type="text" id="subtitle_bn" name="subtitle_bn" value="{{ old('subtitle_bn') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('subtitle_bn') border-red-500 @enderror">
                            @error('subtitle_bn')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-4">
                            <label for="description_bn" class="block text-sm font-medium text-gray-900">{{ __('app.slide_description_bn') }}</label>
                            <textarea id="description_bn" name="description_bn" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description_bn') border-red-500 @enderror">{{ old('description_bn') }}</textarea>
                            @error('description_bn')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-4">
                            <label for="button_text_bn" class="block text-sm font-medium text-gray-900">{{ __('app.button_text_bn') }}</label>
                            <input type="text" id="button_text_bn" name="button_text_bn" value="{{ old('button_text_bn') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('button_text_bn') border-red-500 @enderror">
                            @error('button_text_bn')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Button URL -->
                    <div>
                        <label for="button_url" class="block text-sm font-medium text-gray-900">
                            {{ __('app.button_url') }}
                        </label>
                        <input type="url" 
                               id="button_url" 
                               name="button_url" 
                               value="{{ old('button_url') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('button_url') border-red-500 @enderror"
                               placeholder="https://example.com">
                        @error('button_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-900">
                            {{ __('app.slide_image') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors" id="image-upload-area">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>{{ __('app.upload') }}</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*" required onchange="previewImage(this)">
                                    </label>
                                    <p class="pl-1">{{ __('app.drag_drop_files') }}</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF {{ __('app.file_size_limit', ['size' => 2]) }}</p>
                                <div id="image-preview" class="hidden mt-2">
                                    <img id="preview-img" src="" alt="{{ __('app.file_preview') }}" class="mx-auto h-32 w-auto object-cover rounded-lg border">
                                    <p class="text-xs text-green-600 mt-1">{{ __('app.upload_complete') }}</p>
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-900">
                            {{ __('app.display_order') }}
                        </label>
                        <input type="number" 
                               id="order" 
                               name="order" 
                               value="{{ old('order') }}"
                               min="1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('order') border-red-500 @enderror"
                               placeholder="{{ __('app.display_order') }} ({{ __('app.optional') }})">
                        <p class="mt-1 text-sm text-gray-500">{{ __('app.leave_empty_to_add_end') }}</p>
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            {{ __('app.active') }} ({{ __('app.visible_on_homepage') }})
                        </label>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.carousel.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>{{ __('app.create_slide') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const file = input.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        // Validate file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            input.value = '';
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, or GIF)');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('image-upload-area');
    const fileInput = document.getElementById('image');
    
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-blue-400', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            previewImage(fileInput);
        }
    });
});
</script>
@endpush
        </div>
    </div>
</div>
@endsection
