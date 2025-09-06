@extends('layouts.app')

@section('title', __('app.add_staff'))

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('staff.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.name') }}</label>
                        <input name="name" value="{{ old('name') }}" class="mt-1 block w-full border rounded px-3 py-2">
                        @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full border rounded px-3 py-2">
                        @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.password') }}</label>
                        <input type="password" name="password" class="mt-1 block w-full border rounded px-3 py-2">
                        @error('password')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.employee_id') }}</label>
                        <input name="employee_id" value="{{ old('employee_id') }}" class="mt-1 block w-full border rounded px-3 py-2">
                        @error('employee_id')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.designation') }}</label>
                        <input name="designation" value="{{ old('designation') }}" class="mt-1 block w-full border rounded px-3 py-2">
                        @error('designation')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.department') }}</label>
                        <input name="department" value="{{ old('department') }}" class="mt-1 block w-full border rounded px-3 py-2">
                        @error('department')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.salary') }}</label>
                        <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" class="mt-1 block w-full border rounded px-3 py-2">
                        @error('salary')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.joining_date') }}</label>
                        <input type="date" name="joining_date" value="{{ old('joining_date') }}" class="mt-1 block w-full border rounded px-3 py-2">
                        @error('joining_date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('app.experience') }}</label>
                    <textarea name="experience" class="mt-1 block w-full border rounded px-3 py-2" rows="3">{{ old('experience') }}</textarea>
                    @error('experience')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded" checked>
                    <label for="is_active" class="text-sm text-gray-700">{{ __('app.active') }}</label>
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('staff.index') }}" class="px-4 py-2 rounded border">{{ __('app.cancel') }}</a>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('app.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection


