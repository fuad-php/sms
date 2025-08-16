@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Announcements</h1>
        @can('create', App\Models\Announcement::class)
        <a href="{{ route('announcements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>New Announcement
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('announcements.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Search announcements...">
            </div>
            
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select name="priority" id="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                            {{ ucfirst($priority) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                <select name="target_audience" id="target_audience" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Audiences</option>
                    @foreach($audiences as $audience)
                        <option value="{{ $audience }}" {{ request('target_audience') == $audience ? 'selected' : '' }}>
                            {{ ucfirst($audience) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            
            <div class="md:col-span-2 lg:col-span-4 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('announcements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Announcements List -->
    <div class="space-y-4">
        @forelse($announcements as $announcement)
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">{{ $announcement->getPriorityIcon() }}</span>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $announcement->title }}</h3>
                        <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->getPriorityBadgeClass() }}">
                                {{ ucfirst($announcement->priority) }}
                            </span>
                            <span>{{ $announcement->getTargetAudienceText() }}</span>
                            @if($announcement->class)
                                <span class="text-blue-600">{{ $announcement->class->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    @if($announcement->hasAttachment())
                        <a href="{{ route('announcements.download', $announcement) }}" 
                           class="text-blue-600 hover:text-blue-800" title="Download Attachment">
                            <i class="fas fa-paperclip"></i>
                        </a>
                    @endif
                    
                    @can('update', $announcement)
                        <a href="{{ route('announcements.edit', $announcement) }}" 
                           class="text-blue-600 hover:text-blue-800" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form method="POST" action="{{ route('announcements.toggle-publish', $announcement) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-{{ $announcement->is_published ? 'green' : 'yellow' }}-600 hover:text-{{ $announcement->is_published ? 'green' : 'yellow' }}-800" 
                                    title="{{ $announcement->is_published ? 'Unpublish' : 'Publish' }}">
                                <i class="fas fa-{{ $announcement->is_published ? 'eye' : 'eye-slash' }}"></i>
                            </button>
                        </form>
                    @endcan
                    
                    @can('delete', $announcement)
                        <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
            
            <div class="prose max-w-none mb-4">
                <p class="text-gray-700">{{ Str::limit($announcement->content, 200) }}</p>
            </div>
            
            <div class="flex justify-between items-center text-sm text-gray-500">
                <div class="flex items-center space-x-4">
                    <span>By {{ $announcement->createdBy->name }}</span>
                    <span>{{ $announcement->created_at->diffForHumans() }}</span>
                    @if($announcement->publish_date)
                        <span>Published: {{ $announcement->publish_date->format('M d, Y') }}</span>
                    @endif
                    @if($announcement->expire_date)
                        <span class="text-{{ $announcement->isExpired() ? 'red' : 'orange' }}-600">
                            {{ $announcement->getTimeRemaining() }}
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center space-x-2">
                    @if($announcement->isActive())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @elseif($announcement->isScheduled())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Scheduled
                        </span>
                    @elseif($announcement->isExpired())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Expired
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Draft
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('announcements.show', $announcement) }}" 
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    Read More <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-bullhorn text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Announcements Found</h3>
            <p class="text-gray-600 mb-4">There are no announcements matching your criteria.</p>
            @can('create', App\Models\Announcement::class)
            <a href="{{ route('announcements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                Create First Announcement
            </a>
            @endcan
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($announcements->hasPages())
    <div class="mt-8">
        {{ $announcements->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
