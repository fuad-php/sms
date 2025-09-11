@props(['items' => []])

@if(!empty($items))
<nav class="flex" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-4">
        @foreach($items as $index => $item)
            @if($index === 0)
                <!-- Home icon for first item -->
                <li>
                    <div>
                        <a href="{{ $item['url'] ?? route('school.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            <span class="sr-only">{{ $item['label'] ?? __('app.home') }}</span>
                        </a>
                    </div>
                </li>
            @else
                <!-- Breadcrumb separator and item -->
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        @if($index === count($items) - 1)
                            <!-- Last item (current page) -->
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ $item['label'] }}</span>
                        @else
                            <!-- Linkable item -->
                            <a href="{{ $item['url'] }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                {{ $item['label'] }}
                            </a>
                        @endif
                    </div>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif
