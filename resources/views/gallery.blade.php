<x-public-layout>
    <main class="min-h-screen">
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('app.gallery') }}</h1>
                        <p class="text-gray-600">{{ __('app.explore_school_gallery') }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-2 text-sm rounded border view-toggle" data-view="grid">{{ __('app.grid_view') }}</button>
                        <button class="px-3 py-2 text-sm rounded border view-toggle" data-view="masonry">{{ __('app.masonry_view') }}</button>
                        <button class="px-3 py-2 text-sm rounded border view-toggle" data-view="list">{{ __('app.list_view') }}</button>
                        <button class="px-3 py-2 text-sm rounded border view-toggle" data-view="compact">{{ __('app.compact_view') }}</button>
                    </div>
                </div>

                <div id="gallery-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($images as $img)
                        <div class="group bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden border {{ $img->is_featured ? 'ring-2 ring-yellow-400' : '' }}">
                            <div class="relative">
                                <img src="{{ $img->image_url }}" alt="{{ $img->title }}" class="w-full h-56 object-cover">
                                @if($img->is_featured)
                                    <span class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-semibold px-2 py-1 rounded">{{ __('app.featured') }}</span>
                                @endif
                            </div>
                            @if($img->title || $img->description)
                            <div class="p-4">
                                @if($img->title)
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $img->title }}</h3>
                                @endif
                                @if($img->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($img->description, 120) }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">{{ $images->links() }}</div>
            </div>
        </section>
    </main>

    @push('scripts')
    <script>
        document.querySelectorAll('.view-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const view = btn.dataset.view;
                const container = document.getElementById('gallery-container');
                container.className = '';
                if (view === 'grid') {
                    container.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6';
                } else if (view === 'masonry') {
                    container.className = 'columns-1 sm:columns-2 lg:columns-3 gap-6';
                    // Tailwind masonry via CSS columns
                    Array.from(container.children).forEach(card => { card.classList.add('break-inside-avoid'); });
                } else if (view === 'list') {
                    container.className = 'space-y-4';
                    Array.from(container.children).forEach(card => { card.classList.add('flex'); card.querySelector('img').classList.add('w-56','h-40'); });
                } else if (view === 'compact') {
                    container.className = 'grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3';
                    Array.from(container.children).forEach(card => { const img = card.querySelector('img'); if (img) { img.classList.remove('h-56'); img.classList.add('h-28'); }});
                }
            });
        });
    </script>
    @endpush
</x-public-layout>


