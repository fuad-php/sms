<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">@schoolName</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Back to Home</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <!-- Success/Error Messages -->
                    @if (session('status') === 'profile-updated')
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">Profile updated successfully!</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-2xl">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Profile Information</h2>
                            <p class="mt-1 text-sm text-gray-600 mb-8">Update your profile information and settings.</p>
                            
                            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                @method('patch')

                                <!-- Profile Image Section -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Picture</h3>
                                    <div class="flex items-center space-x-6">
                                        <div class="flex-shrink-0">
                                            <img id="profile-image-preview" 
                                                 src="{{ $user->profile_image_url }}" 
                                                 alt="{{ __('app.profile_picture') }}" 
                                                 class="h-20 w-20 rounded-full object-cover border-2 border-gray-300">
                                        </div>
                                        <div class="flex-1">
                                            <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">
                                                Upload Profile Image
                                            </label>
                                            <input id="profile_image" 
                                                   name="profile_image" 
                                                   type="file" 
                                                   accept="image/*"
                                                   onchange="previewProfileImage(this)"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                            @error('profile_image')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Basic Information -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                            <input id="name" 
                                                   name="name" 
                                                   type="text" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                                   value="{{ old('name', $user->name) }}" 
                                                   required 
                                                   autofocus 
                                                   autocomplete="name" />
                                            @error('name')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                            <input id="email" 
                                                   name="email" 
                                                   type="email" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                                   value="{{ old('email', $user->email) }}" 
                                                   required 
                                                   autocomplete="username" />
                                            @error('email')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                            <input id="phone" 
                                                   name="phone" 
                                                   type="tel" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                                   value="{{ old('phone', $user->phone) }}" 
                                                   autocomplete="tel" />
                                            @error('phone')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                            <input id="contact_number" 
                                                   name="contact_number" 
                                                   type="tel" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                                   value="{{ old('contact_number', $user->contact_number) }}" 
                                                   autocomplete="tel" />
                                            @error('contact_number')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                        <textarea id="address" 
                                                  name="address" 
                                                  rows="3" 
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                                  autocomplete="address">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Personal Information -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                            <input id="date_of_birth" 
                                                   name="date_of_birth" 
                                                   type="date" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                                   value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" />
                                            @error('date_of_birth')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                            <select id="gender" 
                                                    name="gender" 
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('gender')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="blood_group" class="block text-sm font-medium text-gray-700">Blood Group</label>
                                            <select id="blood_group" 
                                                    name="blood_group" 
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select Blood Group</option>
                                                <option value="A+" {{ old('blood_group', $user->blood_group) === 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('blood_group', $user->blood_group) === 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('blood_group', $user->blood_group) === 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('blood_group', $user->blood_group) === 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ old('blood_group', $user->blood_group) === 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('blood_group', $user->blood_group) === 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ old('blood_group', $user->blood_group) === 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('blood_group', $user->blood_group) === 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                            @error('blood_group')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex items-center justify-end space-x-4">
                                    <a href="{{ route('dashboard') }}" 
                                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium transition-colors">
                                        Cancel
                                    </a>
                                    <button type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function previewProfileImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('profile-image-preview').src = e.target.result;
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const fileInput = document.getElementById('profile_image');
            
            // File size validation
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    if (file.size > maxSize) {
                        alert('File size must be less than 2MB');
                        this.value = '';
                        return;
                    }
                    
                    // File type validation
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Please select a valid image file (JPEG, PNG, JPG, GIF)');
                        this.value = '';
                        return;
                    }
                }
            });
        });
    </script>
</body>
</html>
