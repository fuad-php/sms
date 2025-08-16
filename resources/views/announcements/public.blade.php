<x-public-layout>
    <div class="py-8">

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">School Announcements</h1>
                        <p class="text-gray-600">Stay updated with the latest news and important information from our school.</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">{{ $announcements->total() }}</div>
                        <div class="text-sm text-gray-500">Total Announcements</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <form method="GET" action="{{ route('announcements.public') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Search announcements..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="min-w-48">
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select name="priority" 
                                id="priority"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Priorities</option>
                            @foreach($priorities as $priority)
                                <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                    {{ ucfirst($priority) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Filter
                        </button>
                        @if(request('search') || request('priority'))
                            <a href="{{ route('announcements.public') }}" class="ml-2 bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Announcements List -->
            @if($announcements->count() > 0)
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($announcements as $announcement)
                        <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200">
                            <!-- Priority Badge -->
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->getPriorityBadgeClass() }}">
                                            {{ $announcement->getPriorityIcon() }} {{ ucfirst($announcement->priority) }}
                                        </span>
                                        @if($announcement->created_at->diffInDays(now()) <= 7)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                🆕 New
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        {{ $announcement->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    {{ $announcement->title }}
                                </h3>
                                <div class="text-gray-600 text-sm mb-4 line-clamp-3" id="content-{{ $announcement->id }}">
                                    {!! Str::limit(strip_tags($announcement->content), 150) !!}
                                </div>
                                @if(strlen(strip_tags($announcement->content)) > 150)
                                    <div class="text-gray-600 text-sm mb-4 hidden" id="full-content-{{ $announcement->id }}">
                                        {!! nl2br(e($announcement->content)) !!}
                                    </div>
                                @endif

                                <!-- Meta Information -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <span>By {{ $announcement->createdBy->name }}</span>
                                    <div class="flex items-center space-x-2">
                                        @if($announcement->class)
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                                {{ $announcement->class->name }}
                                            </span>
                                        @endif
                                        @if($announcement->expire_date)
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                                ⏰ {{ $announcement->getTimeRemaining() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        @if(strlen(strip_tags($announcement->content)) > 150)
                                            <button onclick="toggleContent({{ $announcement->id }})" 
                                                    class="text-blue-600 hover:text-blue-800 font-medium text-sm toggle-content-btn" 
                                                    id="toggle-{{ $announcement->id }}">
                                                Show More
                                            </button>
                                        @endif
                                        <a href="{{ route('announcements.show', $announcement) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                            View Details →
                                        </a>
                                    </div>
                                    @if($announcement->hasAttachment())
                                        <a href="{{ route('announcements.download', $announcement) }}" 
                                           class="text-gray-500 hover:text-gray-700 text-sm">
                                            📎 Attachment
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($announcements->hasPages())
                    <div class="mt-8">
                        {{ $announcements->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No announcements found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request('search') || request('priority'))
                            Try adjusting your search criteria.
                        @else
                            Check back later for new announcements.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        function toggleContent(announcementId) {
            const contentDiv = document.getElementById(`content-${announcementId}`);
            const fullContentDiv = document.getElementById(`full-content-${announcementId}`);
            const toggleBtn = document.getElementById(`toggle-${announcementId}`);
            
            if (fullContentDiv.classList.contains('hidden')) {
                // Show full content
                contentDiv.classList.add('hidden');
                fullContentDiv.classList.remove('hidden');
                toggleBtn.textContent = 'Show Less';
            } else {
                // Show truncated content
                contentDiv.classList.remove('hidden');
                fullContentDiv.classList.add('hidden');
                toggleBtn.textContent = 'Show More';
            }
        }
    </script>
</x-public-layout>
