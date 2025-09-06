<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.gradebook') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.class') }}</label>
                        <select name="class_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">-- {{ __('app.select_class') }} --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ (string)$classId === (string)$class->id ? 'selected' : '' }}>{{ $class->full_name ?? $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.subject') }}</label>
                        <select name="subject_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">-- {{ __('app.select_subject') }} --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ (string)$subjectId === (string)$subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2 flex items-end">
                        <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">{{ __('app.filter') }}</button>
                    </div>
                </form>

                @if($exams->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-3 py-2 text-left">{{ __('app.student') }}</th>
                                    @foreach($exams as $exam)
                                        <th class="px-3 py-2 text-left">
                                            <div class="font-semibold">{{ $exam->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $exam->exam_date->format('d M Y') }} • {{ $exam->total_marks }}</div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resultsByStudent as $studentId => $results)
                                    @php $first = $results->first(); @endphp
                                    <tr class="border-b">
                                        <td class="px-3 py-2">
                                            {{ $first->student->user->name }}
                                        </td>
                                        @foreach($exams as $exam)
                                            @php $r = $results->firstWhere('exam_id', $exam->id); @endphp
                                            <td class="px-3 py-2">
                                                @if($r)
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $r->is_absent ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                        {{ $r->is_absent ? __('app.absent') : $r->marks_obtained }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">{{ __('app.select_class_subject_to_view_gradebook') }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
