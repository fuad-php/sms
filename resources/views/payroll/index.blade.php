@extends('layouts.app')

@section('title', __('app.payroll'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <a href="{{ route('payroll.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>{{ __('app.generate_payroll') }}
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-2">
                @if($users->count())
                    <select name="user_id" class="border rounded px-3 py-2">
                        <option value="">{{ __('app.all_users') }}</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                @endif
                <select name="month" class="border rounded px-3 py-2">
                    <option value="">{{ __('app.month') }}</option>
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" @selected(request('month')==$m)>{{ $m }}</option>
                    @endfor
                </select>
                <input type="number" name="year" value="{{ request('year') }}" placeholder="{{ __('app.year') }}" class="border rounded px-3 py-2">
                <div></div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('app.filter') }}</button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.user') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.month') }}/{{ __('app.year') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.basic_salary') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.allowances') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.deductions') }}</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.net_salary') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($items as $row)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $row->user->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $row->month }}/{{ $row->year }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($row->basic_salary, 2) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($row->allowances, 2) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($row->deductions, 2) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 font-semibold">{{ number_format($row->net_salary, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">{{ __('app.no_records_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
</div>
@endsection


