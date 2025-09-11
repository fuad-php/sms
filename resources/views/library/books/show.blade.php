@extends('layouts.app')

@section('title', $book->title)

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
                                    <span class="ml-4 text-sm font-medium text-gray-500">{{ $book->title }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        {{ $book->title }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('app.by') }} {{ $book->author }}
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('library.books.edit', $book) }}" 
                       class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('app.edit') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Book Details -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Book Cover -->
                            <div class="flex justify-center">
                                @if($book->cover_image)
                                    <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" 
                                         class="h-64 w-48 object-cover rounded-lg shadow-lg">
                                @else
                                    <div class="h-64 w-48 bg-gray-200 rounded-lg shadow-lg flex items-center justify-center">
                                        <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Book Information -->
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ __('app.basic_information') }}</h3>
                                    <dl class="mt-2 space-y-2">
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">{{ __('app.title') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ $book->title }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">{{ __('app.author') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ $book->author }}</dd>
                                        </div>
                                        @if($book->publisher)
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500">{{ __('app.publisher') }}:</dt>
                                                <dd class="text-sm text-gray-900">{{ $book->publisher }}</dd>
                                            </div>
                                        @endif
                                        @if($book->publication_year)
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500">{{ __('app.publication_year') }}:</dt>
                                                <dd class="text-sm text-gray-900">{{ $book->publication_year }}</dd>
                                            </div>
                                        @endif
                                        @if($book->isbn)
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500">{{ __('app.isbn') }}:</dt>
                                                <dd class="text-sm text-gray-900">{{ $book->isbn }}</dd>
                                            </div>
                                        @endif
                                        @if($book->call_number)
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500">{{ __('app.call_number') }}:</dt>
                                                <dd class="text-sm text-gray-900">{{ $book->call_number }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ __('app.category_classification') }}</h3>
                                    <dl class="mt-2 space-y-2">
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">{{ __('app.category') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ $book->category->name ?? __('app.uncategorized') }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">{{ __('app.book_type') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $book->book_type)) }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ __('app.inventory_information') }}</h3>
                                    <dl class="mt-2 space-y-2">
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">{{ __('app.total_copies') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ $book->total_copies }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">{{ __('app.available_copies') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ $book->available_copies }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-500">{{ __('app.issued_copies') }}:</dt>
                                            <dd class="text-sm text-gray-900">{{ $book->issued_copies }}</dd>
                                        </div>
                                        @if($book->price)
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500">{{ __('app.price') }}:</dt>
                                                <dd class="text-sm text-gray-900">${{ number_format($book->price, 2) }}</dd>
                                            </div>
                                        @endif
                                        @if($book->acquisition_date)
                                            <div class="flex justify-between">
                                                <dt class="text-sm font-medium text-gray-500">{{ __('app.acquisition_date') }}:</dt>
                                                <dd class="text-sm text-gray-900">{{ $book->acquisition_date->format('M d, Y') }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>

                        @if($book->description)
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('app.description') }}</h3>
                                <p class="text-sm text-gray-700">{{ $book->description }}</p>
                            </div>
                        @endif

                        @if($book->notes)
                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('app.notes') }}</h3>
                                <p class="text-sm text-gray-700">{{ $book->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status and Actions -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.status_actions') }}</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">{{ __('app.status') }}:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $book->status_badge_class }}">
                                    {{ ucfirst($book->status) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">{{ __('app.condition') }}:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $book->condition_badge_class }}">
                                    {{ ucfirst($book->condition) }}
                                </span>
                            </div>

                            @if($book->is_featured)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500">{{ __('app.featured') }}:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ __('app.yes') }}
                                    </span>
                                </div>
                            @endif

                            <div class="pt-4 border-t">
                                <div class="flex space-x-2">
                                    <a href="{{ route('library.books.edit', $book) }}" 
                                       class="flex-1 bg-blue-50 text-blue-700 text-sm font-medium py-2 px-3 rounded-md hover:bg-blue-100 text-center">
                                        {{ __('app.edit_book') }}
                                    </a>
                                    @if($book->available_copies > 0)
                                        <button onclick="openIssueModal()" 
                                                class="flex-1 bg-green-50 text-green-700 text-sm font-medium py-2 px-3 rounded-md hover:bg-green-100 text-center">
                                            {{ __('app.issue_book') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Issues -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.recent_issues') }}</h3>
                        @if($book->issues->count() > 0)
                            <div class="space-y-3">
                                @foreach($book->issues->take(5) as $issue)
                                    <div class="flex items-center justify-between text-sm">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $issue->borrower_name }}</p>
                                            <p class="text-gray-500">{{ $issue->issue_date->format('M d, Y') }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $issue->issue_status_badge_class }}">
                                            {{ $issue->issue_status_text }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            @if($book->issues->count() > 5)
                                <div class="mt-4">
                                    <a href="{{ route('library.issues') }}?book_id={{ $book->id }}" 
                                       class="text-sm text-blue-600 hover:text-blue-500">
                                        {{ __('app.view_all_issues') }}
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500">{{ __('app.no_issues_yet') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Issue Book Modal -->
<div id="issueModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.issue_book') }}</h3>
            <form action="{{ route('library.issue-book') }}" method="POST">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <div class="space-y-4">
                    <div>
                        <label for="borrower_id" class="block text-sm font-medium text-gray-700">
                            {{ __('app.borrower_id') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="borrower_id" id="borrower_id" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="{{ __('app.enter_borrower_id') }}">
                    </div>

                    <div>
                        <label for="borrower_type" class="block text-sm font-medium text-gray-700">
                            {{ __('app.borrower_type') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="borrower_type" id="borrower_type" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('app.select_type') }}</option>
                            <option value="student">{{ __('app.student') }}</option>
                            <option value="teacher">{{ __('app.teacher') }}</option>
                            <option value="staff">{{ __('app.staff') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">
                            {{ __('app.due_date') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="due_date" id="due_date" required
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeIssueModal()" 
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        {{ __('app.cancel') }}
                    </button>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        {{ __('app.issue_book') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openIssueModal() {
    document.getElementById('issueModal').classList.remove('hidden');
}

function closeIssueModal() {
    document.getElementById('issueModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('issueModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeIssueModal();
    }
});
</script>
@endsection
