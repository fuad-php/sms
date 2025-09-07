@extends('layouts.app')

@section('title', __('app.create_student'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('app.create_new_student') }}</h1>
        <p class="text-gray-600">{{ __('app.add_new_student_to_system') }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @if ($errors->any())
            <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">{{ __('app.there_were_errors') }}</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('app.user_information') }}</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('app.full_name') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                               @error('name') aria-invalid="true" data-error="1" @enderror>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('app.email') }} <span class="text-red-600">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                               @error('email') aria-invalid="true" data-error="1" @enderror>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('app.password') }} <span class="text-red-600">*</span></label>
                        <input type="password" name="password" id="password" required
                               class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                               @error('password') aria-invalid="true" data-error="1" @enderror>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('app.phone') }}</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">{{ __('app.address') }}</label>
                        <textarea name="address" id="address" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">{{ __('app.birth_date') }} <span class="text-red-600">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required
                               class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('date_of_birth') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                               @error('date_of_birth') aria-invalid="true" data-error="1" @enderror>
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">{{ __('app.gender') }} <span class="text-red-600">*</span></label>
                        <select name="gender" id="gender" required class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('gender') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                                @error('gender') aria-invalid="true" data-error="1" @enderror>
                            <option value="">{{ __('app.select_gender') }}</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('app.male') }}</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('app.female') }}</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('app.other') }}</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Student Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('app.student_information') }}</h3>
                    
                    <div>
                        <label for="mother_name" class="block text-sm font-medium text-gray-700">{{ __('app.mother_name') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name') }}" required
                               class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('mother_name') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                               @error('mother_name') aria-invalid="true" data-error="1" @enderror>
                        @error('mother_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="father_name" class="block text-sm font-medium text-gray-700">{{ __('app.father_name') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="father_name" id="father_name" value="{{ old('father_name') }}" required
                               class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('father_name') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                               @error('father_name') aria-invalid="true" data-error="1" @enderror>
                        @error('father_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_registration" class="block text-sm font-medium text-gray-700">{{ __('app.birth_registration') }}</label>
                        <input type="text" name="birth_registration" id="birth_registration" value="{{ old('birth_registration') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('birth_registration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('app.student_id') }}</label>
                        <input type="text" value="{{ __('app.auto_generated') }}" disabled
                               class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">{{ __('app.student_id_auto_note') }}</p>
                    </div>

                    <div>
                        <label for="roll_number" class="block text-sm font-medium text-gray-700">{{ __('app.roll_number') }} <span class="text-gray-400">({{ __('app.optional') }})</span></label>
                        <input type="number" name="roll_number" id="roll_number" value="{{ old('roll_number') }}" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">{{ __('app.leave_blank_for_auto_roll') }}</p>
                        @error('roll_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">{{ __('app.class') }} <span class="text-red-600">*</span></label>
                        <select name="class_id" id="class_id" required
                                class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('class_id') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                                @error('class_id') aria-invalid="true" data-error="1" @enderror>
                            <option value="">{{ __('app.select_class') }}</option>
                            @foreach(\App\Models\SchoolClass::active()->get() as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admission_date" class="block text-sm font-medium text-gray-700">{{ __('app.admission_date') }} <span class="text-red-600">*</span></label>
                        <input type="date" name="admission_date" id="admission_date" value="{{ old('admission_date') }}" required
                               class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('admission_date') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                               @error('admission_date') aria-invalid="true" data-error="1" @enderror>
                        @error('admission_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="guardian_name" class="block text-sm font-medium text-gray-700">{{ __('app.parent_guardian_name') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="guardian_name" id="guardian_name" value="{{ old('guardian_name') }}" required
                               class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('guardian_name') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                               @error('guardian_name') aria-invalid="true" data-error="1" @enderror>
                        @error('guardian_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="guardian_phone" class="block text-sm font-medium text-gray-700">{{ __('app.parent_guardian_phone') }} <span class="text-red-600">*</span></label>
                        <input type="tel" name="guardian_phone" id="guardian_phone" value="{{ old('guardian_phone') }}" required
                               class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('guardian_phone') border-red-500 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 @enderror"
                               @error('guardian_phone') aria-invalid="true" data-error="1" @enderror>
                        @error('guardian_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700">{{ __('app.profile_picture') }}</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('students.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('app.cancel') }}
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('app.create_student') }}
                </button>
            </div>
            <script>
                (function() {
                    const firstError = document.querySelector('[data-error="1"]');
                    if (firstError && typeof firstError.focus === 'function') {
                        firstError.focus();
                    }
                })();
            </script>
        </form>
    </div>
</div>
@endsection
