@extends('layouts.app')

@section('title', __('app.edit_parent'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.edit_parent') }}</h1>
                    <p class="text-gray-600">{{ __('app.edit_parent_information') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('parents.show', $parent) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ __('app.view_parent') }}
                    </a>
                    <a href="{{ route('parents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('app.back_to_parents') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
            <form method="POST" action="{{ route('parents.update', $parent) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.personal_information') }}</h3>
                    </div>

                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.full_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $parent->user->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_full_name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.email') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $parent->user->email) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.phone') }}
                        </label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $parent->user->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_phone') }}">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.password') }}
                        </label>
                        <input type="password" name="password" id="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_new_password') }}">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">{{ __('app.leave_blank_to_keep_current') }}</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.confirm_password') }}
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="{{ __('app.confirm_password') }}">
                    </div>

                    <!-- Professional Information -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.professional_information') }}</h3>
                    </div>

                    <!-- Occupation -->
                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.occupation') }}
                        </label>
                        <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $parent->occupation) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('occupation') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_occupation') }}">
                        @error('occupation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Workplace -->
                    <div>
                        <label for="workplace" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.workplace') }}
                        </label>
                        <input type="text" name="workplace" id="workplace" value="{{ old('workplace', $parent->workplace) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('workplace') border-red-500 @enderror"
                               placeholder="{{ __('app.enter_workplace') }}">
                        @error('workplace')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Income Range -->
                    <div>
                        <label for="income_range" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.income_range') }}
                        </label>
                        <select name="income_range" id="income_range"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('income_range') border-red-500 @enderror">
                            <option value="">{{ __('app.select_income_range') }}</option>
                            <option value="low" {{ old('income_range', $parent->income_range) == 'low' ? 'selected' : '' }}>{{ __('app.low') }}</option>
                            <option value="medium" {{ old('income_range', $parent->income_range) == 'medium' ? 'selected' : '' }}>{{ __('app.medium') }}</option>
                            <option value="high" {{ old('income_range', $parent->income_range) == 'high' ? 'selected' : '' }}>{{ __('app.high') }}</option>
                        </select>
                        @error('income_range')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('app.notes') }}
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                                  placeholder="{{ __('app.enter_notes') }}">{{ old('notes', $parent->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student Assignment -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.assign_children') }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ __('app.select_children_for_parent') }}</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($students as $student)
                                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                                    <input type="checkbox" name="students[]" value="{{ $student->id }}" id="student_{{ $student->id }}"
                                           {{ in_array($student->id, $parent->students->pluck('id')->toArray()) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="student_{{ $student->id }}" class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->class->name ?? 'N/A' }} - {{ $student->class->section ?? '' }}</div>
                                    </label>
                                    <select name="relationships[]" class="text-sm border border-gray-300 rounded px-2 py-1">
                                        @php
                                            $currentRelationship = $parent->students->where('id', $student->id)->first()?->pivot?->relationship ?? 'guardian';
                                        @endphp
                                        <option value="father" {{ $currentRelationship == 'father' ? 'selected' : '' }}>{{ __('app.father') }}</option>
                                        <option value="mother" {{ $currentRelationship == 'mother' ? 'selected' : '' }}>{{ __('app.mother') }}</option>
                                        <option value="guardian" {{ $currentRelationship == 'guardian' ? 'selected' : '' }}>{{ __('app.guardian') }}</option>
                                        <option value="other" {{ $currentRelationship == 'other' ? 'selected' : '' }}>{{ __('app.other') }}</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($students->count() === 0)
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <p class="mt-2 text-sm">{{ __('app.no_students_available') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('parents.show', $parent) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('app.update_parent') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
