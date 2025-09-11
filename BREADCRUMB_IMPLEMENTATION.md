# Breadcrumb Implementation Guide

This guide explains how to implement breadcrumbs across all blade pages in the School Management System.

## Components Created

### 1. Breadcrumb Component (`resources/views/components/breadcrumb.blade.php`)
A reusable breadcrumb component that displays navigation breadcrumbs with proper styling.

### 2. Page Header Component (`resources/views/components/page-header.blade.php`)
A comprehensive page header component that includes:
- Breadcrumb navigation
- Page title
- Page description
- Action buttons (optional)

### 3. Breadcrumb Helper (`app/Helpers/BreadcrumbHelper.php`)
A helper class that automatically generates breadcrumbs based on the current route.

## How to Implement

### Method 1: Automatic Breadcrumbs (Recommended)
Use the page header component for automatic breadcrumb generation:

```blade
@extends('layouts.app')

@section('title', 'Page Title')

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-page-header>
        <x-slot name="actions">
            <!-- Action buttons go here -->
            <a href="{{ route('some.route') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Action Button
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page content goes here -->
    </div>
</div>
@endsection
```

### Method 2: Custom Breadcrumbs
For custom breadcrumbs, pass them to the page header:

```blade
<x-page-header 
    :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('school.dashboard')],
        ['label' => 'Students', 'url' => route('students.index')],
        ['label' => 'Add Student']
    ]"
    title="Add New Student"
    description="Add a new student to the system"
>
    <x-slot name="actions">
        <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Back to List
        </a>
    </x-slot>
</x-page-header>
```

### Method 3: Manual Breadcrumb Component
Use the breadcrumb component directly:

```blade
<x-breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('school.dashboard')],
    ['label' => 'Students', 'url' => route('students.index')],
    ['label' => 'Add Student']
]" />
```

## Supported Routes

The BreadcrumbHelper automatically generates breadcrumbs for the following routes:

### Student Management
- `students.index` - Students List
- `students.create` - Add Student
- `students.edit` - Edit Student
- `students.show` - Student Details

### Parent Management
- `parents.index` - Parents List
- `parents.create` - Add Parent
- `parents.edit` - Edit Parent

### Attendance
- `attendance.index` - Attendance
- `attendance.class-report` - Class Report
- `attendance.student-report` - Student Report
- `attendance.statistics` - Statistics

### Teacher Management
- `teachers.index` - Teachers List
- `teachers.create` - Add Teacher
- `teachers.edit` - Edit Teacher
- `teachers.show` - Teacher Details

### Academic Management
- `classes.index` - Classes List
- `classes.create` - Add Class
- `classes.edit` - Edit Class
- `subjects.index` - Subjects List
- `subjects.create` - Add Subject
- `subjects.edit` - Edit Subject

### Exams & Results
- `exams.index` - Exams List
- `exams.create` - Add Exam
- `exams.edit` - Edit Exam
- `exams.show` - Exam Details
- `results.index` - Results List
- `results.create` - Add Result
- `results.edit` - Edit Result

### Announcements
- `announcements.index` - Announcements List
- `announcements.create` - Add Announcement
- `announcements.edit` - Edit Announcement
- `announcements.show` - Announcement Details

### Yearly Leave Settings
- `yearly-leaves.index` - Yearly Leave Settings
- `yearly-leaves.create` - Add Yearly Leave
- `yearly-leaves.edit` - Edit Yearly Leave
- `yearly-leaves.show` - Yearly Leave Details

### Events
- `events.index` - Events List
- `events.create` - Add Event
- `events.edit` - Edit Event

### Administration
- `admin.gallery.index` - Gallery
- `settings.index` - Settings
- `settings.group` - Settings Group
- `reports.index` - Reports
- `fees.dashboard` - Fee Management
- `fee-categories.index` - Fee Categories
- `fee-categories.create` - Add Fee Category
- `fee-categories.edit` - Edit Fee Category
- `library.dashboard` - Library
- `library.books.index` - Books List
- `library.books.create` - Add Book
- `library.books.edit` - Edit Book
- `library.books.show` - Book Details
- `admin.contact.index` - Contact Management
- `managing-committees.index` - Managing Committees
- `managing-committees.create` - Add Managing Committee
- `managing-committees.edit` - Edit Managing Committee

## Adding New Routes

To add breadcrumb support for new routes, update the `BreadcrumbHelper::generate()` method:

```php
case 'new-route.index':
    $breadcrumbs[] = ['label' => __('app.new_section')];
    break;
case 'new-route.create':
    $breadcrumbs[] = ['label' => __('app.new_section'), 'url' => route('new-route.index')];
    $breadcrumbs[] = ['label' => __('app.add_new_item')];
    break;
```

## Translation Keys

Add the following translation keys to your language files:

```php
// In lang/en/app.php
'add_student' => 'Add Student',
'edit_student' => 'Edit Student',
'student_details' => 'Student Details',
'add_parent' => 'Add Parent',
'edit_parent' => 'Edit Parent',
// ... and so on for all supported routes
```

## Styling

The breadcrumb component uses Tailwind CSS classes and matches the design system used throughout the application. The styling includes:

- Home icon for the first breadcrumb item
- Chevron separators between items
- Hover effects for clickable items
- Proper spacing and typography
- Responsive design

## Examples

### Simple Page with Actions
```blade
<x-page-header>
    <x-slot name="actions">
        <a href="{{ route('students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Add New Student
        </a>
    </x-slot>
</x-page-header>
```

### Page with Custom Title and Description
```blade
<x-page-header 
    title="Custom Page Title"
    description="Custom page description"
>
    <x-slot name="actions">
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
            Save Changes
        </button>
    </x-slot>
</x-page-header>
```

### Page with Custom Breadcrumbs
```blade
<x-page-header 
    :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('school.dashboard')],
        ['label' => 'Custom Section', 'url' => route('custom.index')],
        ['label' => 'Custom Page']
    ]"
    title="Custom Page"
    description="This is a custom page with custom breadcrumbs"
/>
```

## Benefits

1. **Consistent Navigation**: All pages have consistent breadcrumb navigation
2. **Automatic Generation**: Breadcrumbs are automatically generated based on routes
3. **Easy Customization**: Can be easily customized for specific needs
4. **Responsive Design**: Works well on all screen sizes
5. **Accessibility**: Proper ARIA labels and semantic HTML
6. **Maintainable**: Centralized breadcrumb logic in helper class

## Migration Steps

To migrate existing pages to use the new breadcrumb system:

1. Replace the existing header section with `<x-page-header>`
2. Move action buttons to the `actions` slot
3. Remove manual breadcrumb HTML
4. Update the page structure to use the new layout
5. Test the breadcrumb navigation

This implementation provides a consistent, maintainable, and user-friendly navigation experience across the entire School Management System.
