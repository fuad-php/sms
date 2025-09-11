@extends('layouts.app')

@section('title', 'Teacher Profile - ' . $teacher->user->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('teachers.index') }}" class="mr-4 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('app.back_to_teachers') }}
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $teacher->user->name }}</h1>
                        <p class="text-gray-600">{{ __('app.teacher_profile') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if($teacher->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('app.active') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('app.inactive') }}
                        </span>
                    @endif
                    <a href="{{ route('teachers.edit', $teacher) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('app.edit_profile') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <div class="mx-auto h-32 w-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                @if($teacher->user->profile_image)
                                    <img class="h-32 w-32 object-cover" src="{{ asset('storage/' . $teacher->user->profile_image) }}" alt="{{ $teacher->user->name }}">
                                @else
                                    <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </div>
                            <h3 class="mt-4 text-xl font-medium text-gray-900">{{ $teacher->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $teacher->qualification }}</p>
                            <p class="text-sm text-gray-500">{{ $teacher->specialization }}</p>
                        </div>

                        <!-- Contact Information -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Contact Information</h4>
                            <dl class="space-y-2">
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $teacher->user->email }}</span>
                                </div>
                                @if($teacher->user->phone)
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $teacher->user->phone }}</span>
                                </div>
                                @endif
                                @if($teacher->user->contact_number)
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $teacher->user->contact_number }}</span>
                                </div>
                                @endif
                                @if($teacher->user->address)
                                <div class="flex items-start">
                                    <svg class="h-4 w-4 text-gray-400 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $teacher->user->address }}</span>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Personal Information -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Personal Information</h4>
                            <dl class="space-y-2">
                                @if($teacher->user->date_of_birth)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Date of Birth:</dt>
                                    <dd class="text-sm text-gray-900">{{ $teacher->user->date_of_birth->format('M d, Y') }}</dd>
                                </div>
                                @endif
                                @if($teacher->user->gender)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Gender:</dt>
                                    <dd class="text-sm text-gray-900 capitalize">{{ $teacher->user->gender }}</dd>
                                </div>
                                @endif
                                @if($teacher->user->blood_group)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Blood Group:</dt>
                                    <dd class="text-sm text-gray-900">{{ $teacher->user->blood_group }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Professional Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Information</h3>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Employee ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $teacher->employee_id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Qualification</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $teacher->qualification }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Specialization</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $teacher->specialization }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Experience</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $teacher->experience }} years</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Salary</dt>
                                <dd class="mt-1 text-sm text-gray-900">${{ number_format($teacher->salary, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Joining Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $teacher->joining_date->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Subject Assignments -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Subject Assignments</h3>
                            <button onclick="assignSubjects()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Assign Subjects
                            </button>
                        </div>
                        
                        @if($teacher->subjects->count() > 0)
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periods/Week</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($teacher->subjects as $assignment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $assignment->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $assignment->class_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $assignment->pivot->periods_per_week }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($assignment->pivot->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No subjects assigned</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by assigning subjects to this teacher.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Class Teacher Assignments -->
                @if($teacher->classesAsTeacher->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Class Teacher Assignments</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($teacher->classesAsTeacher as $class)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900">{{ $class->class_with_section }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $class->students->count() }} students</p>
                                <p class="text-xs text-gray-400 mt-2">Class Teacher</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Account Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Account Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $teacher->user->created_at->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('app.last_updated') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $teacher->user->updated_at->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                                <dd class="mt-1">
                                    @if($teacher->user->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subject Assignment Modal -->
<div id="subjectAssignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Assign Subjects</h3>
                <button onclick="closeSubjectModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="subjectAssignmentForm">
                <div id="subject-assignments-modal">
                    <!-- Subject assignments will be added here dynamically -->
                </div>
                
                <div class="flex justify-between items-center mt-6">
                    <button type="button" onclick="addSubjectAssignmentModal()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Assignment
                    </button>
                    
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeSubjectModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('app.cancel') }}
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('app.save_assignments') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let subjectAssignmentIndex = 0;

function assignSubjects() {
    document.getElementById('subjectAssignmentModal').classList.remove('hidden');
    addSubjectAssignmentModal();
}

function closeSubjectModal() {
    document.getElementById('subjectAssignmentModal').classList.add('hidden');
    document.getElementById('subject-assignments-modal').innerHTML = '';
    subjectAssignmentIndex = 0;
}

function addSubjectAssignmentModal() {
    const container = document.getElementById('subject-assignments-modal');
    const newAssignment = document.createElement('div');
    newAssignment.className = 'subject-assignment border border-gray-200 rounded-lg p-4 mb-4';
    newAssignment.innerHTML = `
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Subject</label>
                <select name="subjects[${subjectAssignmentIndex}][subject_id]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">{{ __('app.select_subject') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Class</label>
                <select name="subjects[${subjectAssignmentIndex}][class_id]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">{{ __('app.select_class') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Periods per Week</label>
                <input type="number" name="subjects[${subjectAssignmentIndex}][periods_per_week]" min="1" max="10" value="1"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>
        <button type="button" onclick="removeSubjectAssignmentModal(this)" class="mt-2 text-sm text-red-600 hover:text-red-800">
            {{ __('app.remove_assignment') }}
        </button>
    `;
    container.appendChild(newAssignment);
    
    // Populate the dropdowns with data
    populateDropdowns(newAssignment);
    
    subjectAssignmentIndex++;
}

function populateDropdowns(assignmentElement) {
    const subjectSelect = assignmentElement.querySelector('select[name*="[subject_id]"]');
    const classSelect = assignmentElement.querySelector('select[name*="[class_id]"]');
    
    // Populate subjects dropdown
    subjectsData.forEach(subject => {
        const option = document.createElement('option');
        option.value = subject.id;
        option.textContent = subject.name + (subject.code ? ` (${subject.code})` : '');
        subjectSelect.appendChild(option);
    });
    
    // Populate classes dropdown
    classesData.forEach(classItem => {
        const option = document.createElement('option');
        option.value = classItem.id;
        option.textContent = classItem.display_name;
        classSelect.appendChild(option);
    });
}

function removeSubjectAssignmentModal(button) {
    button.parentElement.remove();
}

// Handle form submission
document.getElementById('subjectAssignmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const assignments = [];
    
    // Collect all assignment data
    const assignmentElements = document.querySelectorAll('.subject-assignment');
    assignmentElements.forEach((element, index) => {
        const subjectId = element.querySelector('select[name*="[subject_id]"]').value;
        const classId = element.querySelector('select[name*="[class_id]"]').value;
        const periodsPerWeek = element.querySelector('input[name*="[periods_per_week]"]').value;
        
        if (subjectId && classId && periodsPerWeek) {
            assignments.push({
                subject_id: subjectId,
                class_id: classId,
                periods_per_week: periodsPerWeek
            });
        }
    });
    
    if (assignments.length === 0) {
        alert('{{ __('app.please_add_subject_assignment') }}');
        return;
    }
    
    // Submit the data
    fetch('{{ route("teachers.assign-subjects", $teacher->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            subjects: assignments
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Subject assignments updated successfully!');
            closeSubjectModal();
            location.reload(); // Reload the page to show updated assignments
        } else {
            alert('Error updating subject assignments: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __('app.error_updating_assignments') }}');
    });
});

// Global variables to store subjects and classes data
let subjectsData = [];
let classesData = [];

// Load subjects and classes for the modal
document.addEventListener('DOMContentLoaded', function() {
    loadSubjectsAndClasses();
});

function loadSubjectsAndClasses() {
    // Load subjects
    fetch('{{ route("teachers.ajax.subjects") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            subjectsData = data;
            console.log('Subjects loaded:', subjectsData.length);
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
            alert('{{ __('app.error_loading_subjects') }}');
        });

    // Load classes
    fetch('{{ route("teachers.ajax.classes") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            classesData = data;
            console.log('Classes loaded:', classesData.length);
        })
        .catch(error => {
            console.error('Error loading classes:', error);
            alert('{{ __('app.error_loading_classes') }}');
        });
}
</script>
@endpush
