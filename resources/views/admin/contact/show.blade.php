@extends('layouts.app')

@section('title', __('app.contact_inquiry_details'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('app.contact_inquiry_details') }}</h1>
                    <p class="text-gray-600 mt-2">{{ __('app.view_contact_inquiry_information') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.contact.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>{{ __('app.back_to_contact_list') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Inquiry Details -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $inquiry->subject }}</h2>
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $inquiry->status_badge_class }}">
                            {{ $inquiry->status_text }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ __('app.submitted') }} {{ $inquiry->created_at->format('M j, Y \a\t g:i A') }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.contact_information') }}</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('app.name') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $inquiry->name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('app.email') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <a href="mailto:{{ $inquiry->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $inquiry->email }}
                                    </a>
                                </p>
                            </div>
                            
                            @if($inquiry->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('app.phone') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <a href="tel:{{ $inquiry->phone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $inquiry->phone }}
                                    </a>
                                </p>
                            </div>
                            @endif
                            
                            @if($inquiry->department)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('app.department') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $inquiry->department }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.message_details') }}</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('app.subject') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $inquiry->subject }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('app.message') }}</label>
                                <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $inquiry->message }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Technical Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.technical_information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.ip_address') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $inquiry->ip_address ?: __('app.not_available') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.user_agent') }}</label>
                            <p class="mt-1 text-sm text-gray-900 text-xs break-all">
                                {{ $inquiry->user_agent ?: __('app.not_available') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.actions') }}</h3>
                    <div class="flex flex-wrap gap-3">
                        <!-- Toggle Read Status -->
                        <form method="POST" action="{{ route('admin.contact.toggle-read', $inquiry) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white {{ $inquiry->is_read ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                                <i class="fas fa-{{ $inquiry->is_read ? 'envelope' : 'envelope-open' }} mr-2"></i>
                                {{ $inquiry->is_read ? __('app.mark_as_unread') : __('app.mark_as_read') }}
                            </button>
                        </form>
                        
                        <!-- Reply via Email -->
                        <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ $inquiry->subject }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-reply mr-2"></i>{{ __('app.reply_via_email') }}
                        </a>
                        
                        <!-- Delete -->
                        <form method="POST" action="{{ route('admin.contact.destroy', $inquiry) }}" class="inline" 
                              onsubmit="return confirm('{{ __('app.confirm_delete_contact') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                <i class="fas fa-trash mr-2"></i>{{ __('app.delete_inquiry') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Timestamps -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.timestamps') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('app.created_at') }}</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $inquiry->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('app.updated_at') }}</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $inquiry->updated_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
