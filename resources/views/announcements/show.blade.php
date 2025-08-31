@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            @auth
                <a href="{{ route('announcements.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Announcements
                </a>
            @else
                <a href="{{ route('announcements.public') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Public Announcements
                </a>
            @endauth
        </div>

        <!-- Announcement Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <span class="text-3xl">{{ $announcement->getPriorityIcon() }}</span>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $announcement->title }}</h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-600 mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $announcement->getPriorityBadgeClass() }}">
                                {{ ucfirst($announcement->priority) }}
                            </span>
                            <span>{{ $announcement->getTargetAudienceText() }}</span>
                            @if($announcement->class)
                                <span class="text-blue-600 font-medium">{{ $announcement->class->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    @if($announcement->hasAttachment())
                        @auth
                            <a href="{{ route('announcements.download', $announcement) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition duration-200" 
                               title="Download Attachment">
                                <i class="fas fa-download mr-2"></i>Download
                            </a>
                        @else
                            <a href="{{ route('announcements.public.download', $announcement) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition duration-200" 
                               title="Download Attachment">
                                <i class="fas fa-download mr-2"></i>Download
                            </a>
                        @endauth
                    @endif
                    
                    @can('update', $announcement)
                        <a href="{{ route('announcements.edit', $announcement) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-4">
                @if($announcement->isActive())
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>Active
                    </span>
                @elseif($announcement->isScheduled())
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-2"></i>Scheduled
                    </span>
                @elseif($announcement->isExpired())
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-2"></i>Expired
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-edit mr-2"></i>Draft
                    </span>
                @endif
            </div>

            <!-- Meta Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 border-t border-gray-200 pt-4">
                <div>
                    <span class="font-medium">Created by:</span> {{ $announcement->createdBy->name }}
                </div>
                <div>
                    <span class="font-medium">Created:</span> {{ $announcement->created_at->format('F d, Y \a\t g:i A') }}
                </div>
                @if($announcement->publish_date)
                <div>
                    <span class="font-medium">Published:</span> {{ $announcement->publish_date->format('F d, Y \a\t g:i A') }}
                </div>
                @endif
                @if($announcement->expire_date)
                <div>
                    <span class="font-medium">Expires:</span> 
                    <span class="text-{{ $announcement->isExpired() ? 'red' : 'orange' }}-600">
                        {{ $announcement->expire_date->format('F d, Y \a\t g:i A') }}
                        @if(!$announcement->isExpired())
                            ({{ $announcement->getTimeRemaining() }})
                        @endif
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Announcement Content -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Content</h2>
            <div class="prose max-w-none">
                {!! nl2br(e($announcement->content)) !!}
            </div>
        </div>

        <!-- Attachment Section -->
        @if($announcement->hasAttachment())
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Attachment</h2>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-paperclip text-2xl text-gray-500"></i>
                    <div>
                        <p class="font-medium text-gray-900">{{ $announcement->attachment }}</p>
                        <p class="text-sm text-gray-600">Click to download</p>
                    </div>
                </div>
                @auth
                    <a href="{{ route('announcements.download', $announcement) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-download mr-2"></i>Download
                    </a>
                @else
                    <a href="{{ route('announcements.public.download', $announcement) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-download mr-2"></i>Download
                    </a>
                @endauth
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <div class="flex space-x-4">
                @can('update', $announcement)
                    <form method="POST" action="{{ route('announcements.toggle-publish', $announcement) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-{{ $announcement->is_published ? 'yellow' : 'green' }}-600 hover:bg-{{ $announcement->is_published ? 'yellow' : 'green' }}-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-{{ $announcement->is_published ? 'eye-slash' : 'eye' }} mr-2"></i>
                            {{ $announcement->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>
                @endcan
            </div>
            
            <div class="flex space-x-4">
                @can('delete', $announcement)
                    <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" class="inline" 
                          onsubmit="return confirm('Are you sure you want to delete this announcement? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
