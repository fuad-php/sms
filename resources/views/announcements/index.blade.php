@extends('layouts.app')

@section('title', __('app.announcements'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('app.announcements') }}</h1>
        @can('create', App\Models\Announcement::class)
        <a href="{{ route('announcements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>{{ __('app.new_announcement') }}
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('announcements.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.search') }}</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="{{ __('app.search_announcements') }}">
            </div>
            
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.priority') }}</label>
                <select name="priority" id="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('app.all_priorities') }}</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                            {{ __('app.priority_' . $priority) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.target_audience') }}</label>
                <select name="target_audience" id="target_audience" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('app.all_audiences') }}</option>
                    @foreach($audiences as $audience)
                        <option value="{{ $audience }}" {{ request('target_audience') == $audience ? 'selected' : '' }}>
                            {{ __('app.audience_' . $audience) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.status') }}</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('app.all_status') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ __('app.published') }}</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('app.draft') }}</option>
                </select>
            </div>
            
            <div class="md:col-span-2 lg:col-span-4 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-search mr-2"></i>{{ __('app.filter') }}
                </button>
                <a href="{{ route('announcements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-times mr-2"></i>{{ __('app.clear') }}
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
                           class="text-blue-600 hover:text-blue-800" title="{{ __('app.download_attachment') }}">
                            <i class="fas fa-paperclip"></i>
                        </a>
                    @endif
                    
                    @can('update', $announcement)
                        <a href="{{ route('announcements.edit', $announcement) }}" 
                           class="text-blue-600 hover:text-blue-800" title="{{ __('app.edit') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form method="POST" action="{{ route('announcements.toggle-publish', $announcement) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-{{ $announcement->is_published ? 'green' : 'yellow' }}-600 hover:text-{{ $announcement->is_published ? 'green' : 'yellow' }}-800" 
                                    title="{{ $announcement->is_published ? __('app.unpublish') : __('app.publish') }}">
                                <i class="fas fa-{{ $announcement->is_published ? 'eye' : 'eye-slash' }}"></i>
                            </button>
                        </form>
                    @endcan
                    
                    @can('delete', $announcement)
                        <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" class="inline" 
                              onsubmit="return confirm('{{ __('app.confirm_delete_announcement') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="{{ __('app.delete') }}">
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
                    <span>{{ __('app.by') }} {{ $announcement->createdBy->name }}</span>
                    <span>{{ $announcement->created_at->diffForHumans() }}</span>
                    @if($announcement->publish_date)
                        <span>{{ __('app.published_at') }}: {{ $announcement->publish_date->format('M d, Y') }}</span>
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
                            {{ __('app.active') }}
                        </span>
                    @elseif($announcement->isScheduled())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ __('app.scheduled') }}
                        </span>
                    @elseif($announcement->isExpired())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ __('app.expired') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ __('app.draft') }}
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('announcements.show', $announcement) }}" 
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    {{ __('app.read_more') }} <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-bullhorn text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('app.no_announcements_found') }}</h3>
            <p class="text-gray-600 mb-4">{{ __('app.no_announcements_match_criteria') }}</p>
            @can('create', App\Models\Announcement::class)
            <a href="{{ route('announcements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                {{ __('app.create_first_announcement') }}
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
