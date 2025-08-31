@extends('layouts.app')

@section('title', __('app.add_result'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.add_result') }}</h1>
                    <p class="text-gray-600">{{ __('app.add_new_exam_result') }}</p>
                </div>
                <a href="{{ route('results.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← {{ __('app.back_to_results') }}
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('results.store') }}" class="space-y-6">
                @csrf

                <!-- Exam Selection -->
                <div>
                    <label for="exam_id" class="block text-sm font-medium text-gray-700">{{ __('app.exam') }} <span class="text-red-500">*</span></label>
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

                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700">{{ __('app.student') }} <span class="text-red-500">*</span></label>
                    <select name="student_id" id="student_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('student_id') border-red-500 @enderror">
                        <option value="">{{ __('app.select_student') }}</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->user->name }} - {{ $student->class->name }} ({{ $student->roll_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Absent Status -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_absent" id="is_absent" value="1" {{ old('is_absent') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_absent" class="ml-2 block text-sm text-gray-900">{{ __('app.mark_as_absent') }}</label>
                </div>

                <!-- Marks and Grade (hidden if absent) -->
                <div id="marks_section" class="space-y-4 {{ old('is_absent') ? 'hidden' : '' }}">
                    <!-- Marks Obtained -->
                    <div>
                        <label for="marks_obtained" class="block text-sm font-medium text-gray-700">{{ __('app.marks_obtained') }}</label>
                        <input type="number" name="marks_obtained" id="marks_obtained" step="0.01" min="0" value="{{ old('marks_obtained') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('marks_obtained') border-red-500 @enderror">
                        @error('marks_obtained')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grade -->
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700">{{ __('app.grade') }}</label>
                        <select name="grade" id="grade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('grade') border-red-500 @enderror">
                            <option value="">{{ __('app.auto_calculate') }}</option>
                            <option value="A+" {{ old('grade') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A" {{ old('grade') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B+" {{ old('grade') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B" {{ old('grade') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C+" {{ old('grade') == 'C+' ? 'selected' : '' }}>C+</option>
                            <option value="C" {{ old('grade') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ old('grade') == 'D' ? 'selected' : '' }}>D</option>
                            <option value="F" {{ old('grade') == 'F' ? 'selected' : '' }}>F</option>
                        </select>
                        @error('grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Remarks -->
                <div>
                    <label for="remarks" class="block text-sm font-medium text-gray-700">{{ __('app.remarks') }}</label>
                    <textarea name="remarks" id="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('remarks') border-red-500 @enderror" placeholder="{{ __('app.enter_remarks_optional') }}">{{ old('remarks') }}</textarea>
                    @error('remarks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">{{ __('app.there_were_errors') }}</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('results.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md">
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ __('app.create_result') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const absentCheckbox = document.getElementById('is_absent');
    const marksSection = document.getElementById('marks_section');
    const marksInput = document.getElementById('marks_obtained');
    const gradeSelect = document.getElementById('grade');

    absentCheckbox.addEventListener('change', function() {
        if (this.checked) {
            marksSection.classList.add('hidden');
            marksInput.value = '';
            gradeSelect.value = '';
        } else {
            marksSection.classList.remove('hidden');
        }
    });
});
</script>
@endsection
