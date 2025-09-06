@extends('layouts.app')

@section('title', __('app.apply_leave'))

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-xl p-6">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900">{{ __('app.apply_leave') }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ __('app.apply_leave_help') }}</p>
            </div>
            <form method="POST" action="{{ route('leaves.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.leave_type') }}</label>
                        <select name="type" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            @foreach(['casual','sick','earned','unpaid','maternity','paternity','other'] as $t)
                                <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.start_date') }}</label>
                        <input type="date" name="start_date" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.end_date') }}</label>
                        <input type="date" name="end_date" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('app.reason') }}</label>
                    <textarea name="reason" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" rows="4" placeholder="{{ __('app.reason_placeholder') }}"></textarea>
                    <p class="mt-1 text-xs text-gray-500">{{ __('app.optional') }}</p>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('leaves.index') }}" class="px-4 py-2 rounded-lg border hover:bg-gray-50">{{ __('app.cancel') }}</a>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ __('app.submit') }}</button>
                </div>
            </form>
            @if ($errors->any())
                <div class="mt-6 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                    <div class="font-medium">{{ __('app.there_were_errors') }}</div>
                    <ul class="mt-2 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection


