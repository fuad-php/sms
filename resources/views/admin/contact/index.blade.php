@extends('layouts.app')

@section('title', __('app.contact_management'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('app.contact_management') }}</h1>
                    <p class="text-gray-600 mt-2">{{ __('app.manage_contact_inquiries') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.contact.export', ['format' => 'csv']) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>{{ __('app.export') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('app.total_inquiries') }}</p>
                        <p class="text-2xl font-semibold text-gray-900" id="total-inquiries">-</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-envelope-open text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('app.unread_inquiries') }}</p>
                        <p class="text-2xl font-semibold text-gray-900" id="unread-inquiries">-</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-calendar-day text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('app.today_inquiries') }}</p>
                        <p class="text-2xl font-semibold text-gray-900" id="today-inquiries">-</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-calendar-week text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ __('app.this_week_inquiries') }}</p>
                        <p class="text-2xl font-semibold text-gray-900" id="this-week-inquiries">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.contact.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.status') }}</label>
                        <select id="status" name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('app.all_statuses') }}</option>
                            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>{{ __('app.unread') }}</option>
                            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>{{ __('app.read') }}</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.department') }}</label>
                        <select id="department" name="department" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('app.all_departments') }}</option>
                            <option value="Admissions" {{ request('department') === 'Admissions' ? 'selected' : '' }}>Admissions</option>
                            <option value="Academic" {{ request('department') === 'Academic' ? 'selected' : '' }}>Academic</option>
                            <option value="Administration" {{ request('department') === 'Administration' ? 'selected' : '' }}>Administration</option>
                            <option value="Student Services" {{ request('department') === 'Student Services' ? 'selected' : '' }}>Student Services</option>
                            <option value="IT Support" {{ request('department') === 'IT Support' ? 'selected' : '' }}>IT Support</option>
                            <option value="General Inquiry" {{ request('department') === 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.search') }}</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" 
                               placeholder="{{ __('app.search_placeholder') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-search mr-2"></i>{{ __('app.filter') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Inquiries Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('app.contact_inquiries') }}</h3>
            </div>
            
            @if($inquiries->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.name') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.email') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.subject') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.department') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.created_at') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('app.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($inquiries as $inquiry)
                                <tr class="hover:bg-gray-50 {{ $inquiry->is_read ? '' : 'bg-blue-50' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $inquiry->name }}</div>
                                        @if($inquiry->phone)
                                            <div class="text-sm text-gray-500">{{ $inquiry->phone }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $inquiry->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $inquiry->subject }}">
                                            {{ $inquiry->subject }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $inquiry->department ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $inquiry->status_badge_class }}">
                                            {{ $inquiry->status_text }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $inquiry->created_at->format('M j, Y g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.contact.show', $inquiry) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <form method="POST" action="{{ route('admin.contact.toggle-read', $inquiry) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-{{ $inquiry->is_read ? 'envelope' : 'envelope-open' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('admin.contact.destroy', $inquiry) }}" class="inline" 
                                                  onsubmit="return confirm('{{ __('app.confirm_delete_contact') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $inquiries->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-envelope text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('app.no_contact_inquiries') }}</h3>
                    <p class="text-gray-500">{{ __('app.no_contact_inquiries_description') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load stats
    loadStats();
    
    // Refresh stats every 30 seconds
    setInterval(loadStats, 30000);
});

function loadStats() {
    fetch('{{ route("admin.contact.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-inquiries').textContent = data.total;
            document.getElementById('unread-inquiries').textContent = data.unread;
            document.getElementById('today-inquiries').textContent = data.today;
            document.getElementById('this-week-inquiries').textContent = data.this_week;
        })
        .catch(error => console.error('Error loading stats:', error));
}
</script>
@endpush
@endsection
