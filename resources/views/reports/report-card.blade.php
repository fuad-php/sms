<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.report_card') }} — {{ $student->user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <div class="text-lg font-semibold">{{ $student->user->name }}</div>
                        <div class="text-gray-600 text-sm">{{ __('app.class') }}: {{ $student->class->full_name ?? $student->class->name }}</div>
                        <div class="text-gray-600 text-sm">{{ __('app.roll_number') }}: {{ $student->roll_number ?? '—' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">{{ __('app.exams_count') }}: {{ $summary['exams_count'] }}</div>
                        <div class="text-sm text-gray-500">{{ __('app.average') }}: {{ $summary['average_percentage'] ? number_format($summary['average_percentage'], 2) . '%' : '—' }}</div>
                        <div class="text-sm text-gray-500">{{ __('app.pass_rate') }}: {{ number_format($summary['pass_rate'], 2) }}%</div>
                    </div>
                </div>

                @foreach($bySubject as $subjectName => $subjectResults)
                    <div class="mb-6">
                        <h3 class="text-md font-semibold mb-2">{{ $subjectName }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-3 py-2 text-left">{{ __('app.exam') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('app.date') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('app.marks') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('app.grade') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('app.status') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('app.remarks') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjectResults as $r)
                                        <tr class="border-b">
                                            <td class="px-3 py-2">{{ $r->exam->name }}</td>
                                            <td class="px-3 py-2">{{ optional($r->exam->exam_date)->format('d M Y') }}</td>
                                            <td class="px-3 py-2">{{ $r->is_absent ? '—' : ($r->marks_obtained . ' / ' . $r->exam->total_marks) }}</td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex px-2 py-1 rounded text-xs {{ $r->getGradeBadgeClass() }}">{{ $r->getGradeCalculated() }}</span>
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex px-2 py-1 rounded text-xs {{ $r->getStatusBadgeClass() }}">{{ $r->getStatusText() }}</span>
                                            </td>
                                            <td class="px-3 py-2">{{ $r->remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                <div class="mt-8 text-right">
                    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ __('app.print') }}</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
