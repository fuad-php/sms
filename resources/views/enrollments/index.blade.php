@extends('layouts.app')

@section('title', __('app.enrollments'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('app.enrollments') }}</h1>
        <p class="text-gray-600">{{ $student->user->name }} ({{ $student->student_id }})</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('app.enrollment_history') }}</h2>

            @if (session('success'))
                <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-4 text-red-800">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.class') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.section') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.academic_year') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.enrolled_at') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.notes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($student->enrollments()->with(['class','section'])->orderByDesc('id')->get() as $en)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $en->class->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $en->section->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $en->academic_year ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ optional($en->enrolled_at)->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                        @class([
                                            'bg-blue-50 text-blue-700' => $en->status === 'enrolled',
                                            'bg-green-50 text-green-700' => $en->status === 'promoted',
                                            'bg-yellow-50 text-yellow-700' => $en->status === 'transferred',
                                            'bg-purple-50 text-purple-700' => $en->status === 'graduated',
                                            'bg-red-50 text-red-700' => $en->status === 'withdrawn',
                                        ])
                                    ">
                                        {{ ucfirst($en->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $en->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">{{ __('app.no_data_available') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('app.add_enrollment') }}</h2>
            <form action="{{ route('enrollments.store', $student->id) }}" method="POST" novalidate>
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">{{ __('app.class') }} <span class="text-red-600">*</span></label>
                        <select id="class_id" name="class_id" required
                                class="mt-1 block w-full rounded-md shadow-sm @error('class_id') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-blue-500 focus:ring-blue-500 @enderror"
                                @error('class_id') aria-invalid="true" @enderror>
                            <option value="">{{ __('app.select_class') }}</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="section_id" class="block text-sm font-medium text-gray-700">{{ __('app.section') }}</label>
                        <select id="section_id" name="section_id"
                                class="mt-1 block w-full rounded-md shadow-sm @error('section_id') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-blue-500 focus:ring-blue-500 @enderror">
                            <option value="">{{ __('app.select_section') }}</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="academic_year" class="block text-sm font-medium text-gray-700">{{ __('app.academic_year') }} <span class="text-red-600">*</span></label>
                        <input id="academic_year" name="academic_year" type="text" value="{{ old('academic_year') }}" required
                               placeholder="2025-2026"
                               class="mt-1 block w-full rounded-md shadow-sm @error('academic_year') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-blue-500 focus:ring-blue-500 @enderror"
                               @error('academic_year') aria-invalid="true" @enderror>
                        @error('academic_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="enrolled_at" class="block text-sm font-medium text-gray-700">{{ __('app.enrolled_at') }}</label>
                        <input id="enrolled_at" name="enrolled_at" type="date" value="{{ old('enrolled_at') }}"
                               class="mt-1 block w-full rounded-md shadow-sm @error('enrolled_at') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-blue-500 focus:ring-blue-500 @enderror">
                        @error('enrolled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('app.status') }} <span class="text-red-600">*</span></label>
                        <select id="status" name="status" required
                                class="mt-1 block w-full rounded-md shadow-sm @error('status') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-blue-500 focus:ring-blue-500 @enderror"
                                @error('status') aria-invalid="true" @enderror>
                            @php($statuses = ['enrolled','promoted','transferred','graduated','withdrawn'])
                            @foreach($statuses as $st)
                                <option value="{{ $st }}" {{ old('status', 'enrolled') == $st ? 'selected' : '' }}>{{ __("app.$st") }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('app.notes') }}</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full rounded-md shadow-sm @error('notes') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-blue-500 focus:ring-blue-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">{{ __('app.add_enrollment') }}</button>
                </div>

                <script>
                    (function(){
                        const firstError = document.querySelector('form [aria-invalid="true"]');
                        if (firstError && typeof firstError.focus === 'function') firstError.focus();
                    })();
                </script>
            </form>
        </div>
    </div>
</div>
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.enrollments') }} — {{ $student->user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">{{ __('app.enrollment_history') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-3 py-2 text-left">{{ __('app.academic_year') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('app.class') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('app.section') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('app.enrolled_at') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('app.status') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('app.notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->enrollments->sortByDesc('enrolled_at') as $enrollment)
                                    <tr class="border-b">
                                        <td class="px-3 py-2">{{ $enrollment->academic_year }}</td>
                                        <td class="px-3 py-2">{{ $enrollment->class->full_name ?? $enrollment->class->name }}</td>
                                        <td class="px-3 py-2">{{ $enrollment->section->name ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ optional($enrollment->enrolled_at)->format('d M Y') }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($enrollment->status) }}</td>
                                        <td class="px-3 py-2">{{ $enrollment->notes }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-3 py-6 text-center text-gray-500">{{ __('app.no_enrollments_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('app.add_enrollment') }}</h3>
                    <form action="{{ route('enrollments.store', $student->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.class') }}</label>
                            <select name="class_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">-- {{ __('app.select_class') }} --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->full_name ?? $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.section') }}</label>
                            <select name="section_id" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">-- {{ __('app.select_section') }} --</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.academic_year') }}</label>
                            <input type="text" name="academic_year" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="2024-2025" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.enrolled_at') }}</label>
                            <input type="date" name="enrolled_at" class="mt-1 block w-full border-gray-300 rounded-md" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.status') }}</label>
                            <select name="status" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="enrolled">{{ __('app.enrolled') }}</option>
                                <option value="promoted">{{ __('app.promoted') }}</option>
                                <option value="transferred">{{ __('app.transferred') }}</option>
                                <option value="graduated">{{ __('app.graduated') }}</option>
                                <option value="withdrawn">{{ __('app.withdrawn') }}</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">{{ __('app.notes') }}</label>
                            <input type="text" name="notes" class="mt-1 block w-full border-gray-300 rounded-md" />
                        </div>
                        <div class="md:col-span-3">
                            <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">{{ __('app.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
