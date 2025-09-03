<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // User information
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'contact_number' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',

            // Teacher specific information
            'employee_id' => 'required|string|max:50|unique:teachers,employee_id',
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date|before_or_equal:today',
            'experience' => 'required|integer|min:0|max:50',

            // Subject assignments
            'subjects' => 'nullable|array',
            'subjects.*.subject_id' => 'required_with:subjects|exists:subjects,id',
            'subjects.*.class_id' => 'required_with:subjects|exists:classes,id',
            'subjects.*.periods_per_week' => 'required_with:subjects|integer|min:1|max:10',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Teacher name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'employee_id.required' => 'Employee ID is required.',
            'employee_id.unique' => 'This employee ID is already in use.',
            'qualification.required' => 'Qualification is required.',
            'specialization.required' => 'Specialization is required.',
            'salary.required' => 'Salary is required.',
            'salary.min' => 'Salary must be a positive number.',
            'joining_date.required' => 'Joining date is required.',
            'joining_date.before_or_equal' => 'Joining date cannot be in the future.',
            'experience.required' => 'Years of experience is required.',
            'experience.min' => 'Experience cannot be negative.',
            'experience.max' => 'Experience cannot exceed 50 years.',
            'profile_image.image' => 'Profile image must be an image file.',
            'profile_image.mimes' => 'Profile image must be a JPEG, PNG, JPG, or GIF file.',
            'profile_image.max' => 'Profile image size cannot exceed 2MB.',
            'subjects.*.subject_id.required_with' => 'Subject selection is required.',
            'subjects.*.class_id.required_with' => 'Class selection is required.',
            'subjects.*.periods_per_week.required_with' => 'Periods per week is required.',
            'subjects.*.periods_per_week.min' => 'Periods per week must be at least 1.',
            'subjects.*.periods_per_week.max' => 'Periods per week cannot exceed 10.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'teacher name',
            'email' => 'email address',
            'password' => 'password',
            'phone' => 'phone number',
            'address' => 'address',
            'date_of_birth' => 'date of birth',
            'gender' => 'gender',
            'blood_group' => 'blood group',
            'contact_number' => 'contact number',
            'profile_image' => 'profile image',
            'employee_id' => 'employee ID',
            'qualification' => 'qualification',
            'specialization' => 'specialization',
            'salary' => 'salary',
            'joining_date' => 'joining date',
            'experience' => 'years of experience',
        ];
    }
}
