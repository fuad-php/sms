@extends('layouts.app')

@section('title', $room->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $room->name }} ({{ $room->code }})</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-sm text-gray-500">{{ __('app.capacity') }}</div>
                <div class="text-gray-900">{{ $room->capacity }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">{{ __('app.location') }}</div>
                <div class="text-gray-900">{{ $room->location ?: __('app.not_available') }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">{{ __('app.status') }}</div>
                <div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $room->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $room->is_active ? __('app.active') : __('app.inactive') }}
                    </span>
                </div>
            </div>
        </div>
        @if($room->description)
            <div class="mt-6">
                <div class="text-sm text-gray-500">{{ __('app.description') }}</div>
                <div class="text-gray-900 whitespace-pre-line">{{ $room->description }}</div>
            </div>
        @endif
        <div class="mt-6">
            <a href="{{ route('rooms.index') }}" class="px-4 py-2 border rounded-md text-sm">{{ __('app.back') }}</a>
        </div>
    </div>
</div>
@endsection


