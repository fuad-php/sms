<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('app.committee_member_details') }}
            </h2>
            <div class="flex space-x-2">
                @can('update', $managingCommittee)
                    <a href="{{ route('managing-committees.edit', $managingCommittee) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('app.edit') }}
                    </a>
                @endcan
                <a href="{{ route('managing-committees.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('app.back_to_list') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Profile Image and Basic Info -->
                        <div class="lg:col-span-1">
                            <div class="text-center">
                                <img src="{{ $managingCommittee->image_url }}" 
                                     alt="{{ $managingCommittee->name }}" 
                                     class="mx-auto h-48 w-48 rounded-full object-cover shadow-lg">
                                
                                <h1 class="mt-6 text-2xl font-bold text-gray-900">{{ $managingCommittee->name }}</h1>
                                
                                <div class="mt-2">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                        {{ $managingCommittee->position == 'Chairman' ? 'bg-red-100 text-red-800' : 
                                           ($managingCommittee->position == 'Vice-Chairman' ? 'bg-orange-100 text-orange-800' : 
                                           ($managingCommittee->position == 'Secretary' ? 'bg-blue-100 text-blue-800' : 
                                           ($managingCommittee->position == 'Treasurer' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))) }}">
                                        {{ $managingCommittee->position_display }}
                                    </span>
                                </div>

                                <p class="mt-2 text-lg text-gray-600">{{ $managingCommittee->designation }}</p>

                                <!-- Status Badges -->
                                <div class="mt-4 flex justify-center space-x-2">
                                    @if($managingCommittee->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ __('app.active') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ __('app.inactive') }}
                                        </span>
                                    @endif
                                    
                                    @if($managingCommittee->is_featured)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ __('app.featured') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Information -->
                        <div class="lg:col-span-2">
                            <div class="space-y-6">
                                <!-- Contact Information -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.contact_information') }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if($managingCommittee->email)
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <a href="mailto:{{ $managingCommittee->email }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $managingCommittee->email }}
                                                </a>
                                            </div>
                                        @endif

                                        @if($managingCommittee->phone)
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                <a href="tel:{{ $managingCommittee->phone }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $managingCommittee->phone }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Term Information -->
                                @if($managingCommittee->term_start || $managingCommittee->term_end)
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.term_information') }}</h3>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            @if($managingCommittee->term_duration)
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">{{ __('app.term_duration') }}:</span> {{ $managingCommittee->term_duration }}
                                                </p>
                                            @endif
                                            
                                            @if($managingCommittee->term_start)
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">{{ __('app.term_start') }}:</span> {{ $managingCommittee->term_start->format('F j, Y') }}
                                                </p>
                                            @endif
                                            
                                            @if($managingCommittee->term_end)
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">{{ __('app.term_end') }}:</span> {{ $managingCommittee->term_end->format('F j, Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Biography -->
                                @if($managingCommittee->bio)
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.bio') }}</h3>
                                        <div class="prose max-w-none">
                                            <p class="text-gray-700 leading-relaxed">{{ $managingCommittee->bio }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Additional Information -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.additional_information') }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">{{ __('app.sort_order') }}:</span>
                                            <span class="ml-2 text-sm text-gray-900">{{ $managingCommittee->sort_order }}</span>
                                        </div>
                                        
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">{{ __('app.created_at') }}:</span>
                                            <span class="ml-2 text-sm text-gray-900">{{ $managingCommittee->created_at->format('F j, Y g:i A') }}</span>
                                        </div>
                                        
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">{{ __('app.updated_at') }}:</span>
                                            <span class="ml-2 text-sm text-gray-900">{{ $managingCommittee->updated_at->format('F j, Y g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @can('manageCommittees', App\Models\ManagingCommittee::class)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="flex space-x-4">
                                    <form method="POST" action="{{ route('managing-committees.toggle-status', $managingCommittee) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                            {{ $managingCommittee->is_active ? __('app.deactivate') : __('app.activate') }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('managing-committees.toggle-featured', $managingCommittee) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                            {{ $managingCommittee->is_featured ? __('app.unfeature') : __('app.feature') }}
                                        </button>
                                    </form>
                                </div>

                                <form method="POST" action="{{ route('managing-committees.destroy', $managingCommittee) }}" class="inline" onsubmit="return confirm('{{ __('app.confirm_delete_committee') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('app.delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
