@extends('layouts.app')

@section('title', __('app.generate_payroll'))

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ url('/api/payroll/generate') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if(auth()->user()->role === 'admin')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.user') }}</label>
                            <select name="user_id" class="mt-1 block w-full border rounded px-3 py-2" required>
                                @foreach(\App\Models\User::orderBy('name')->get(['id','name']) as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.month') }}</label>
                        <select name="month" class="mt-1 block w-full border rounded px-3 py-2">
                            @for($m=1;$m<=12;$m++)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.year') }}</label>
                        <input type="number" name="year" value="{{ now()->year }}" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.basic_salary') }}</label>
                        <input type="number" step="0.01" name="basic_salary" class="mt-1 block w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.allowances') }}</label>
                        <input type="number" step="0.01" name="allowances" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.deductions') }}</label>
                        <input type="number" step="0.01" name="deductions" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="mt-1 block w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('payroll.index') }}" class="px-4 py-2 rounded border">{{ __('app.cancel') }}</a>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('app.generate') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
