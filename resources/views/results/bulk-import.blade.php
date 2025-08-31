@extends('layouts.app')

@section('title', __('app.bulk_import_results'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.bulk_import_results') }}</h1>
                    <p class="text-gray-600">{{ __('app.import_multiple_exam_results') }}</p>
                </div>
                <a href="{{ route('results.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← {{ __('app.back_to_results') }}
                </a>
            </div>
        </div>

        <!-- Import Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="POST" action="{{ route('results.bulk-import.store') }}" class="space-y-6">
                @csrf

                <!-- Exam Selection -->
                <div>
                    <label for="exam_id" class="block text-sm font-medium text-gray-700">{{ __('app.select_exam') }} <span class="text-red-500">*</span></label>
                    <select name="exam_id" id="exam_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('exam_id') border-red-500 @enderror">
                        <option value="">{{ __('app.select_exam') }}</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ old('exam_id') == $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }} - {{ $exam->class->name }} ({{ $exam->subject->name }}) - {{ $exam->exam_date->format('M d, Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('exam_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Results Table -->
                <div id="results_table" class="{{ old('exam_id') ? '' : 'hidden' }}">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.student_results') }}</h3>
                    
                    @if($students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('app.student') }}
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('app.marks') }}
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('app.grade') }}
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('app.status') }}
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('app.remarks') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-xs font-medium text-gray-700">
                                                                {{ substr($student->user->name, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $student->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $student->roll_number }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="results[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" 
                                                       name="results[{{ $loop->index }}][marks_obtained]" 
                                                       step="0.01" 
                                                       min="0" 
                                                       class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                       placeholder="0">
                                            </td>
                                            <td class="px-4 py-3">
                                                <select name="results[{{ $loop->index }}][grade]" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                    <option value="">{{ __('app.auto') }}</option>
                                                    <option value="A+">A+</option>
                                                    <option value="A">A</option>
                                                    <option value="B+">B+</option>
                                                    <option value="B">B</option>
                                                    <option value="C+">C+</option>
                                                    <option value="C">C</option>
                                                    <option value="D">D</option>
                                                    <option value="F">F</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center space-x-3">
                                                    <label class="flex items-center">
                                                        <input type="radio" 
                                                               name="results[{{ $loop->index }}][is_absent]" 
                                                               value="0" 
                                                               checked
                                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                                        <span class="ml-2 text-sm text-gray-700">{{ __('app.present') }}</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" 
                                                               name="results[{{ $loop->index }}][is_absent]" 
                                                               value="1" 
                                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                                        <span class="ml-2 text-sm text-gray-700">{{ __('app.absent') }}</span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" 
                                                       name="results[{{ $loop->index }}][remarks]" 
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                       placeholder="{{ __('app.optional_remarks') }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('app.no_students_found') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('app.select_exam_to_see_students') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('results.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md">
                        {{ __('app.cancel') }}
                    </a>
                    @if($students->count() > 0)
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                            {{ __('app.import_results') }}
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">{{ __('app.bulk_import_instructions') }}</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>{{ __('app.select_exam_first') }}</li>
                            <li>{{ __('app.enter_marks_for_present_students') }}</li>
                            <li>{{ __('app.mark_absent_students_appropriately') }}</li>
                            <li>{{ __('app.grades_can_be_auto_calculated') }}</li>
                            <li>{{ __('app.existing_results_will_be_updated') }}</li>
                            <li>{{ __('app.new_results_will_be_created') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const examSelect = document.getElementById('exam_id');
    const resultsTable = document.getElementById('results_table');

    examSelect.addEventListener('change', function() {
        if (this.value) {
            // Reload page with selected exam to get students
            window.location.href = '{{ route("results.bulk-import") }}?exam_id=' + this.value;
        } else {
            resultsTable.classList.add('hidden');
        }
    });

    // Handle absent radio buttons
    const absentRadios = document.querySelectorAll('input[name$="[is_absent]"]');
    absentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const row = this.closest('tr');
            const marksInput = row.querySelector('input[name$="[marks_obtained]"]');
            const gradeSelect = row.querySelector('select[name$="[grade]"]');
            
            if (this.value === '1') { // Absent
                marksInput.value = '';
                marksInput.disabled = true;
                gradeSelect.value = '';
                gradeSelect.disabled = true;
            } else { // Present
                marksInput.disabled = false;
                gradeSelect.disabled = false;
            }
        });
    });
});
</script>
@endsection
