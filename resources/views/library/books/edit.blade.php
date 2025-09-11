@extends('layouts.app')

@section('title', __('app.edit_book'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-4">
                            <li>
                                <div>
                                    <a href="{{ route('library.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                        <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                        </svg>
                                        <span class="sr-only">{{ __('app.home') }}</span>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ route('library.books.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                        {{ __('app.books') }}
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ route('library.books.show', $book) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                        {{ $book->title }}
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="ml-4 text-sm font-medium text-gray-500">{{ __('app.edit') }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        {{ __('app.edit_book') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('app.edit_book_information') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form action="{{ route('library.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="px-4 py-5 sm:p-6">
                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ __('app.basic_information') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Title -->
                                <div class="sm:col-span-2">
                                    <label for="title" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.title') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" 
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('title') border-red-300 @enderror" 
                                               placeholder="{{ __('app.enter_book_title') }}">
                                        @error('title')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Author -->
                                <div>
                                    <label for="author" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.author') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" 
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('author') border-red-300 @enderror" 
                                               placeholder="{{ __('app.enter_author_name') }}">
                                        @error('author')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Publisher -->
                                <div>
                                    <label for="publisher" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.publisher') }}
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="publisher" id="publisher" value="{{ old('publisher', $book->publisher) }}" 
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('publisher') border-red-300 @enderror" 
                                               placeholder="{{ __('app.enter_publisher_name') }}">
                                        @error('publisher')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Publication Year -->
                                <div>
                                    <label for="publication_year" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.publication_year') }}
                                    </label>
                                    <div class="mt-1">
                                        <input type="number" name="publication_year" id="publication_year" value="{{ old('publication_year', $book->publication_year) }}" 
                                               min="1000" max="{{ date('Y') + 1 }}"
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('publication_year') border-red-300 @enderror" 
                                               placeholder="{{ __('app.enter_publication_year') }}">
                                        @error('publication_year')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- ISBN -->
                                <div>
                                    <label for="isbn" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.isbn') }}
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" 
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('isbn') border-red-300 @enderror" 
                                               placeholder="{{ __('app.enter_isbn') }}">
                                        @error('isbn')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Call Number -->
                                <div>
                                    <label for="call_number" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.call_number') }}
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="call_number" id="call_number" value="{{ old('call_number', $book->call_number) }}" 
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('call_number') border-red-300 @enderror" 
                                               placeholder="{{ __('app.enter_call_number') }}">
                                        @error('call_number')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Category and Classification -->
                        <div class="mb-8">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ __('app.category_classification') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Category -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.category') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <select name="category_id" id="category_id" 
                                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('category_id') border-red-300 @enderror">
                                            <option value="">{{ __('app.select_category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Book Type -->
                                <div>
                                    <label for="book_type" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.book_type') }}
                                    </label>
                                    <div class="mt-1">
                                        <select name="book_type" id="book_type" 
                                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('book_type') border-red-300 @enderror">
                                            <option value="textbook" {{ old('book_type', $book->book_type) == 'textbook' ? 'selected' : '' }}>{{ __('app.textbook') }}</option>
                                            <option value="reference" {{ old('book_type', $book->book_type) == 'reference' ? 'selected' : '' }}>{{ __('app.reference') }}</option>
                                            <option value="fiction" {{ old('book_type', $book->book_type) == 'fiction' ? 'selected' : '' }}>{{ __('app.fiction') }}</option>
                                            <option value="non_fiction" {{ old('book_type', $book->book_type) == 'non_fiction' ? 'selected' : '' }}>{{ __('app.non_fiction') }}</option>
                                            <option value="magazine" {{ old('book_type', $book->book_type) == 'magazine' ? 'selected' : '' }}>{{ __('app.magazine') }}</option>
                                            <option value="journal" {{ old('book_type', $book->book_type) == 'journal' ? 'selected' : '' }}>{{ __('app.journal') }}</option>
                                        </select>
                                        @error('book_type')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Information -->
                        <div class="mb-8">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ __('app.inventory_information') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                <!-- Total Copies -->
                                <div>
                                    <label for="total_copies" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.total_copies') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input type="number" name="total_copies" id="total_copies" value="{{ old('total_copies', $book->total_copies) }}" 
                                               min="1" max="1000"
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('total_copies') border-red-300 @enderror">
                                        @error('total_copies')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.price') }}
                                    </label>
                                    <div class="mt-1">
                                        <input type="number" name="price" id="price" value="{{ old('price', $book->price) }}" 
                                               min="0" step="0.01"
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('price') border-red-300 @enderror" 
                                               placeholder="0.00">
                                        @error('price')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Source -->
                                <div>
                                    <label for="source" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.source') }}
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="source" id="source" value="{{ old('source', $book->source) }}" 
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('source') border-red-300 @enderror" 
                                               placeholder="{{ __('app.enter_source') }}">
                                        @error('source')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Acquisition Date -->
                                <div>
                                    <label for="acquisition_date" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.acquisition_date') }}
                                    </label>
                                    <div class="mt-1">
                                        <input type="date" name="acquisition_date" id="acquisition_date" value="{{ old('acquisition_date', $book->acquisition_date?->format('Y-m-d')) }}" 
                                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('acquisition_date') border-red-300 @enderror">
                                        @error('acquisition_date')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description and Additional Information -->
                        <div class="mb-8">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ __('app.description_additional_info') }}
                            </h3>
                            
                            <div class="space-y-6">
                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.description') }}
                                    </label>
                                    <div class="mt-1">
                                        <textarea name="description" id="description" rows="4" 
                                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-300 @enderror" 
                                                  placeholder="{{ __('app.enter_book_description') }}">{{ old('description', $book->description) }}</textarea>
                                        @error('description')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Cover Image -->
                                <div>
                                    <label for="cover_image" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.cover_image') }}
                                    </label>
                                    @if($book->cover_image)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" 
                                                 class="h-32 w-24 object-cover rounded-lg">
                                            <p class="mt-1 text-sm text-gray-500">{{ __('app.current_cover_image') }}</p>
                                        </div>
                                    @endif
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="cover_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>{{ __('app.upload_file') }}</span>
                                                    <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*">
                                                </label>
                                                <p class="pl-1">{{ __('app.or_drag_drop') }}</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                PNG, JPG, GIF up to 10MB
                                            </p>
                                        </div>
                                    </div>
                                    @error('cover_image')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.notes') }}
                                    </label>
                                    <div class="mt-1">
                                        <textarea name="notes" id="notes" rows="3" 
                                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('notes') border-red-300 @enderror" 
                                                  placeholder="{{ __('app.enter_additional_notes') }}">{{ old('notes', $book->notes) }}</textarea>
                                        @error('notes')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status and Settings -->
                        <div class="mb-8">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ __('app.status_settings') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.status') }}
                                    </label>
                                    <div class="mt-1">
                                        <select name="status" id="status" 
                                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('status') border-red-300 @enderror">
                                            <option value="available" {{ old('status', $book->status) == 'available' ? 'selected' : '' }}>{{ __('app.available') }}</option>
                                            <option value="unavailable" {{ old('status', $book->status) == 'unavailable' ? 'selected' : '' }}>{{ __('app.unavailable') }}</option>
                                            <option value="maintenance" {{ old('status', $book->status) == 'maintenance' ? 'selected' : '' }}>{{ __('app.maintenance') }}</option>
                                        </select>
                                        @error('status')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Condition -->
                                <div>
                                    <label for="condition" class="block text-sm font-medium text-gray-700">
                                        {{ __('app.condition') }}
                                    </label>
                                    <div class="mt-1">
                                        <select name="condition" id="condition" 
                                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('condition') border-red-300 @enderror">
                                            <option value="excellent" {{ old('condition', $book->condition) == 'excellent' ? 'selected' : '' }}>{{ __('app.excellent') }}</option>
                                            <option value="good" {{ old('condition', $book->condition) == 'good' ? 'selected' : '' }}>{{ __('app.good') }}</option>
                                            <option value="fair" {{ old('condition', $book->condition) == 'fair' ? 'selected' : '' }}>{{ __('app.fair') }}</option>
                                            <option value="poor" {{ old('condition', $book->condition) == 'poor' ? 'selected' : '' }}>{{ __('app.poor') }}</option>
                                        </select>
                                        @error('condition')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Featured -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                        {{ __('app.featured_book') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('library.books.show', $book) }}" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('app.cancel') }}
                            </a>
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('app.update_book') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
