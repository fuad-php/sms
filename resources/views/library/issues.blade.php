@extends('layouts.app')

@section('title', __('app.book_issues'))

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
                                    <span class="ml-4 text-sm font-medium text-gray-500">{{ __('app.book_issues') }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        {{ __('app.book_issues') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('app.manage_book_borrowing') }}
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <button type="button" onclick="openIssueModal()" 
                            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('app.issue_book') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('library.issues') }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                {{ __('app.status') }}
                            </label>
                            <div class="mt-1">
                                <select name="status" id="status" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">{{ __('app.all_statuses') }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>{{ __('app.returned') }}</option>
                                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>{{ __('app.overdue') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Book Filter -->
                        <div>
                            <label for="book_id" class="block text-sm font-medium text-gray-700">
                                {{ __('app.book') }}
                            </label>
                            <div class="mt-1">
                                <select name="book_id" id="book_id" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">{{ __('app.all_books') }}</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                                            {{ $book->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Borrower Type Filter -->
                        <div>
                            <label for="borrower_type" class="block text-sm font-medium text-gray-700">
                                {{ __('app.borrower_type') }}
                            </label>
                            <div class="mt-1">
                                <select name="borrower_type" id="borrower_type" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">{{ __('app.all_types') }}</option>
                                    <option value="student" {{ request('borrower_type') == 'student' ? 'selected' : '' }}>{{ __('app.student') }}</option>
                                    <option value="teacher" {{ request('borrower_type') == 'teacher' ? 'selected' : '' }}>{{ __('app.teacher') }}</option>
                                    <option value="staff" {{ request('borrower_type') == 'staff' ? 'selected' : '' }}>{{ __('app.staff') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">
                                {{ __('app.search') }}
                            </label>
                            <div class="mt-1">
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="{{ __('app.search_issues') }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('library.issues') }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            {{ __('app.clear_filters') }}
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            {{ __('app.filter') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Issues Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                @if($issues->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.book') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.borrower') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.issue_date') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.due_date') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.status') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('app.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($issues as $issue)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($issue->book->cover_image)
                                                        <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::url($issue->book->cover_image) }}" alt="{{ $issue->book->title }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $issue->book->title }}</div>
                                                    <div class="text-sm text-gray-500">{{ $issue->book->author }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $issue->borrower_name }}</div>
                                            <div class="text-sm text-gray-500">{{ ucfirst($issue->borrower_type) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $issue->issue_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $issue->due_date->format('M d, Y') }}
                                            @if($issue->isOverdue())
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    {{ __('app.overdue') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $issue->issue_status_badge_class }}">
                                                {{ $issue->issue_status_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if($issue->status === 'active')
                                                    <form action="{{ route('library.return-book', $issue) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-green-600 hover:text-green-900"
                                                                onclick="return confirm('{{ __('app.confirm_return') }}')">
                                                            {{ __('app.return') }}
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('library.renew-book', $issue) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-blue-600 hover:text-blue-900"
                                                                onclick="return confirm('{{ __('app.confirm_renew') }}')">
                                                            {{ __('app.renew') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($issues->hasPages())
                        <div class="mt-6">
                            {{ $issues->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('app.no_issues_found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('app.get_started_by_issuing_books') }}</p>
                        <div class="mt-6">
                            <button type="button" onclick="openIssueModal()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                {{ __('app.issue_book') }}
                            </button>
                        </div>
                    </div>
                @endif
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
                <div class="space-y-4">
                    <div>
                        <label for="modal_book_id" class="block text-sm font-medium text-gray-700">
                            {{ __('app.book') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="book_id" id="modal_book_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('app.select_book') }}</option>
                            @foreach($books as $book)
                                @if($book->available_copies > 0)
                                    <option value="{{ $book->id }}">{{ $book->title }} ({{ $book->available_copies }} {{ __('app.available') }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

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
