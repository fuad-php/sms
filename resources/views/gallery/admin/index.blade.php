@extends('layouts.app')

@section('title', __('app.gallery'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('app.gallery') }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.gallery.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">{{ __('app.add') }}</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($images as $img)
            <div class="bg-white rounded-xl shadow overflow-hidden border">
                <div class="relative">
                    <img src="{{ $img->image_url }}" alt="{{ $img->title }}" class="w-full h-56 object-cover">
                    @if($img->is_featured)
                        <span class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-semibold px-2 py-1 rounded">{{ __('app.featured') }}</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $img->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($img->description, 120) }}</p>
                    <div class="flex items-center justify-between mt-4">
                        <form method="POST" action="{{ route('admin.gallery.toggle-featured', $img) }}">
                            @csrf
                            @method('PATCH')
                            <button class="text-sm px-3 py-1 rounded border">{{ $img->is_featured ? __('app.unfeature') : __('app.feature') }}</button>
                        </form>
                        <div class="space-x-2">
                            <a href="{{ route('admin.gallery.edit', $img) }}" class="text-blue-600">{{ __('app.edit') }}</a>
                            <form method="POST" action="{{ route('admin.gallery.destroy', $img) }}" class="inline" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">{{ __('app.delete') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500">{{ __('app.no_data_found') }}</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $images->links() }}</div>
</div>
@endsection


