@extends('layouts.app')

@section('title', __('app.events'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">{{ __('app.create_event') }}</a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.title') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.location') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.type') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($events as $event)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $event->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $event->start_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $event->location }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $event->type }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 text-right space-x-2">
                        <form method="POST" action="{{ route('events.toggle-publish', $event) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button class="text-{{ $event->is_published ? 'yellow' : 'green' }}-600">{{ $event->is_published ? __('app.unpublish') : __('app.publish') }}</button>
                        </form>
                        <a href="{{ route('events.edit', $event) }}" class="text-blue-600">{{ __('app.edit') }}</a>
                        <form method="POST" action="{{ route('events.destroy', $event) }}" class="inline" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">{{ __('app.delete') }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-4 text-center text-gray-500" colspan="5">{{ __('app.no_events_available') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $events->links() }}</div>
    </div>
</div>
@endsection


