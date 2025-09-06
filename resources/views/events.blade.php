<x-public-layout>
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900">{{ __('app.events') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('app.stay_updated_with_latest') }}</p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-md border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($events as $event)
                <div class="rounded-lg shadow-sm border overflow-hidden bg-white">
                    @if($event->image_url)
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-44 object-cover">
                    @endif
                    <div class="p-5">
                        <div class="text-sm text-gray-500">{{ $event->start_at->format('M d, Y') }} @if($event->end_at) - {{ $event->end_at->format('M d, Y') }} @endif</div>
                        <h3 class="mt-1 text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                        @if($event->location)
                            <div class="mt-1 text-sm text-gray-600">{{ $event->location }}</div>
                        @endif
                        <p class="mt-3 text-sm text-gray-700 line-clamp-3">{{ Str::limit($event->description, 140) }}</p>
                        <div class="mt-4">
                            <a href="{{ route('announcements.public') }}" class="inline-flex items-center text-sm text-blue-600 hover:underline">
                                {{ __('app.read_more') }}
                                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 text-center text-gray-500">
                    {{ __('app.no_events_available') }}
                </div>
            @endforelse
        </div>

        @if(method_exists($events, 'links'))
            <div class="mt-8">{{ $events->links() }}</div>
        @endif
    </div>
</div>
</x-public-layout>


