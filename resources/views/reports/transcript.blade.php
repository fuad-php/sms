<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.transcript') }} — {{ $student->user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <div class="text-lg font-semibold">{{ $student->user->name }}</div>
                        <div class="text-gray-600 text-sm">{{ __('app.class') }}: {{ $student->class->full_name ?? $student->class->name }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">{{ __('app.overall_gpa') }}: {{ $overallGpa ?? '—' }}</div>
                    </div>
                </div>

                @forelse($years as $year)
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-md font-semibold">{{ __('app.academic_year') }}: {{ $year['year'] }}</h3>
                            <div class="text-sm text-gray-600">{{ __('app.gpa') }}: {{ $year['gpa'] ?? '—' }}</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-3 py-2 text-left">{{ __('app.date') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('app.subject') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('app.exam') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('app.marks') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('app.grade') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($year['results'] as $r)
                                        <tr class="border-b">
                                            <td class="px-3 py-2">{{ optional($r->exam->exam_date)->format('d M Y') }}</td>
                                            <td class="px-3 py-2">{{ $r->exam->subject->name }}</td>
                                            <td class="px-3 py-2">{{ $r->exam->name }}</td>
                                            <td class="px-3 py-2">{{ $r->is_absent ? __('app.absent') : ($r->marks_obtained . ' / ' . $r->exam->total_marks) }}</td>
                                            <td class="px-3 py-2">{{ $r->getGradeCalculated() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">{{ __('app.no_results_available') }}</p>
                @endforelse

                <div class="mt-8 text-right">
                    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ __('app.print') }}</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
