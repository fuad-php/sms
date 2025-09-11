@extends('layouts.app')

@section('title', __('app.employee_attendance'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('employee-attendance.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>{{ __('app.mark_employee_attendance') }}
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-2">
                <select name="user_id" class="border rounded px-3 py-2">
                    <option value="">{{ __('app.all_users') }}</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date') }}" class="border rounded px-3 py-2">
                <div></div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('app.filter') }}</button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.date') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.name') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.check_in') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.check_out') }}</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($records as $rec)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $rec->date->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $rec->user->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 capitalize">{{ $rec->status }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $rec->check_in_time ? \Carbon\Carbon::parse($rec->check_in_time)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $rec->check_out_time ? \Carbon\Carbon::parse($rec->check_out_time)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ url('/api/employee-attendance?user_id='.$rec->user_id.'&date='.$rec->date->format('Y-m-d')) }}" target="_blank" class="text-blue-600 hover:underline">{{ __('app.view') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">{{ __('app.no_records_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $records->links() }}</div>
        </div>
    </div>
</div>
@endsection


