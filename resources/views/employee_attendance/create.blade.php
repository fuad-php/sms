@extends('layouts.app')

@section('title', __('app.mark_employee_attendance'))

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ url('/api/employee-attendance/mark') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.user') }}</label>
                        <select name="user_id" class="mt-1 block w-full border rounded px-3 py-2" required>
                            @foreach(\App\Models\User::whereIn('role', ['teacher','staff','admin'])->orderBy('name')->get() as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.date') }}</label>
                        <input type="date" name="date" value="{{ now()->toDateString() }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.status') }}</label>
                        <select name="status" class="mt-1 block w-full border rounded px-3 py-2">
                            @foreach(['present','absent','late','half_day','leave'] as $s)
                                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.check_in') }}</label>
                        <input type="time" name="check_in_time" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.check_out') }}</label>
                        <input type="time" name="check_out_time" class="mt-1 block w-full border rounded px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('app.remarks') }}</label>
                    <textarea name="remarks" class="mt-1 block w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('employee-attendance.index') }}" class="px-4 py-2 rounded border">{{ __('app.cancel') }}</a>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('app.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection


