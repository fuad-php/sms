@extends('layouts.app')

@section('title', __('app.books'))

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
                                    <span class="ml-4 text-sm font-medium text-gray-500">{{ __('app.books') }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        {{ __('app.books') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('app.manage_library_books') }}
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    <a href="{{ route('library.export', request()->query()) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('app.export_csv') }}
                    </a>
                    <a href="{{ route('library.books.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('app.add_book') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Advanced Filters and Search -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('library.books.index') }}" class="space-y-6">
                    <!-- Search Section -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.advanced_search') }}
                        </label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md pl-10" 
                                   placeholder="{{ __('app.search_books_advanced') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">{{ __('app.search_hint') }}</p>
                    </div>

                    <!-- Basic Filters -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">
                                {{ __('app.category') }}
                            </label>
                            <div class="mt-1">
                                <select name="category" id="category" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">{{ __('app.all_categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                {{ __('app.status') }}
                            </label>
                            <div class="mt-1">
                                <select name="status" id="status" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">{{ __('app.all_statuses') }}</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Book Type Filter -->
                        <div>
                            <label for="book_type" class="block text-sm font-medium text-gray-700">
                                {{ __('app.book_type') }}
                            </label>
                            <div class="mt-1">
                                <select name="book_type" id="book_type" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">{{ __('app.all_types') }}</option>
                                    @foreach($bookTypes as $type)
                                        <option value="{{ $type }}" {{ request('book_type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Availability Filter -->
                        <div>
                            <label for="availability" class="block text-sm font-medium text-gray-700">
                                {{ __('app.availability') }}
                            </label>
                            <div class="mt-1">
                                <select name="availability" id="availability" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">{{ __('app.all_availability') }}</option>
                                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>{{ __('app.available') }}</option>
                                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>{{ __('app.unavailable') }}</option>
                                    <option value="overdue" {{ request('availability') == 'overdue' ? 'selected' : '' }}>{{ __('app.overdue') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Filters -->
                    <div class="border-t pt-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('app.advanced_filters') }}</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <!-- Condition Filter -->
                            <div>
                                <label for="condition" class="block text-sm font-medium text-gray-700">
                                    {{ __('app.condition') }}
                                </label>
                                <div class="mt-1">
                                    <select name="condition" id="condition" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">{{ __('app.all_conditions') }}</option>
                                        @foreach($conditions as $condition)
                                            <option value="{{ $condition }}" {{ request('condition') == $condition ? 'selected' : '' }}>
                                                {{ ucfirst($condition) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Publication Year Range -->
                            <div>
                                <label for="year_from" class="block text-sm font-medium text-gray-700">
                                    {{ __('app.year_from') }}
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="year_from" id="year_from" value="{{ request('year_from') }}" 
                                           min="{{ $yearRange->min_year ?? 1900 }}" max="{{ $yearRange->max_year ?? date('Y') }}"
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                           placeholder="{{ __('app.from_year') }}">
                                </div>
                            </div>

                            <div>
                                <label for="year_to" class="block text-sm font-medium text-gray-700">
                                    {{ __('app.year_to') }}
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="year_to" id="year_to" value="{{ request('year_to') }}" 
                                           min="{{ $yearRange->min_year ?? 1900 }}" max="{{ $yearRange->max_year ?? date('Y') }}"
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                           placeholder="{{ __('app.to_year') }}">
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <label for="price_from" class="block text-sm font-medium text-gray-700">
                                    {{ __('app.price_from') }}
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="price_from" id="price_from" value="{{ request('price_from') }}" 
                                           min="0" step="0.01"
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                           placeholder="{{ __('app.min_price') }}">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mt-4">
                            <div>
                                <label for="price_to" class="block text-sm font-medium text-gray-700">
                                    {{ __('app.price_to') }}
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="price_to" id="price_to" value="{{ request('price_to') }}" 
                                           min="0" step="0.01"
                                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                           placeholder="{{ __('app.max_price') }}">
                                </div>
                            </div>

                            <!-- Sort Options -->
                            <div>
                                <label for="sort_by" class="block text-sm font-medium text-gray-700">
                                    {{ __('app.sort_by') }}
                                </label>
                                <div class="mt-1">
                                    <select name="sort_by" id="sort_by" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>{{ __('app.date_added') }}</option>
                                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>{{ __('app.title') }}</option>
                                        <option value="author" {{ request('sort_by') == 'author' ? 'selected' : '' }}>{{ __('app.author') }}</option>
                                        <option value="publication_year" {{ request('sort_by') == 'publication_year' ? 'selected' : '' }}>{{ __('app.publication_year') }}</option>
                                        <option value="total_issues" {{ request('sort_by') == 'total_issues' ? 'selected' : '' }}>{{ __('app.popularity') }}</option>
                                        <option value="average_rating" {{ request('sort_by') == 'average_rating' ? 'selected' : '' }}>{{ __('app.rating') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700">
                                    {{ __('app.sort_order') }}
                                </label>
                                <div class="mt-1">
                                    <select name="sort_order" id="sort_order" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>{{ __('app.descending') }}</option>
                                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>{{ __('app.ascending') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Per Page -->
                            <div>
                                <label for="per_page" class="block text-sm font-medium text-gray-700">
                                    {{ __('app.per_page') }}
                                </label>
                                <div class="mt-1">
                                    <select name="per_page" id="per_page" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="12" {{ request('per_page') == '12' ? 'selected' : '' }}>12</option>
                                        <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20</option>
                                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Special Filters -->
                        <div class="mt-4">
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('app.featured_books') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="new_arrivals" value="1" {{ request('new_arrivals') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('app.new_arrivals') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t">
                        <div class="text-sm text-gray-500">
                            {{ __('app.showing') }} {{ $books->firstItem() ?? 0 }} {{ __('app.to') }} {{ $books->lastItem() ?? 0 }} 
                            {{ __('app.of') }} {{ $books->total() }} {{ __('app.results') }}
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('library.books.index') }}" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                                {{ __('app.clear_filters') }}
                            </a>
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                {{ __('app.apply_filters') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                @if($books->count() > 0)
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach($books as $book)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <!-- Book Cover -->
                                <div class="aspect-w-3 aspect-h-4 bg-gray-200 rounded-t-lg overflow-hidden">
                                    @if($book->cover_image)
                                        <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" 
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Book Info -->
                                <div class="p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-900 truncate" title="{{ $book->title }}">
                                                {{ $book->title }}
                                            </h3>
                                            <p class="text-sm text-gray-500 truncate" title="{{ $book->author }}">
                                                {{ __('app.by') }} {{ $book->author }}
                                            </p>
                                            @if($book->publisher)
                                                <p class="text-xs text-gray-400 truncate" title="{{ $book->publisher }}">
                                                    {{ $book->publisher }}
                                                </p>
                                            @endif
                                        </div>
                                        @if($book->is_featured)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ __('app.featured') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Book Details -->
                                    <div class="mt-3 space-y-2">
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ __('app.category') }}:</span>
                                            <span class="font-medium">{{ $book->category->name ?? __('app.uncategorized') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ __('app.type') }}:</span>
                                            <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $book->book_type)) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ __('app.copies') }}:</span>
                                            <span class="font-medium">{{ $book->available_copies }}/{{ $book->total_copies }}</span>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="mt-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $book->status_badge_class }}">
                                            {{ ucfirst($book->status) }}
                                        </span>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-4 flex space-x-2">
                                        <a href="{{ route('library.books.show', $book) }}" 
                                           class="flex-1 bg-blue-50 text-blue-700 text-xs font-medium py-2 px-3 rounded-md hover:bg-blue-100 text-center">
                                            {{ __('app.view') }}
                                        </a>
                                        <a href="{{ route('library.books.edit', $book) }}" 
                                           class="flex-1 bg-gray-50 text-gray-700 text-xs font-medium py-2 px-3 rounded-md hover:bg-gray-100 text-center">
                                            {{ __('app.edit') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($books->hasPages())
                        <div class="mt-6">
                            {{ $books->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('app.no_books_found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request()->hasAny(['search', 'category', 'status', 'book_type']))
                                {{ __('app.try_adjusting_filters') }}
                            @else
                                {{ __('app.get_started_by_adding_books') }}
                            @endif
                        </p>
                        <div class="mt-6">
                            @if(request()->hasAny(['search', 'category', 'status', 'book_type']))
                                <a href="{{ route('library.books.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    {{ __('app.clear_filters') }}
                                </a>
                            @else
                                <a href="{{ route('library.books.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    {{ __('app.add_book') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
