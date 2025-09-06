@extends('layouts.app')

@section('title', __('app.staff'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" class="mb-4 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search staff...') }}" class="border rounded px-3 py-2 w-full">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('app.search') }}</button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.name') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.email') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.employee_id') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.designation') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.department') }}</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($staff as $item)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $item->id }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $item->user->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $item->user->email }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $item->employee_id }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $item->designation }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $item->department }}</td>
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ url('/api/staff/'.$item->id) }}" target="_blank" class="text-blue-600 hover:underline">{{ __('app.view') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">{{ __('app.no_records_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $staff->links() }}</div>
        </div>
    </div>
@endsection


