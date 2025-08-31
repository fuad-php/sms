<x-public-layout>
    <main class="py-8">
                <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('app.contact_us') }}</h1>
            <p class="text-xl text-gray-600">{{ __('app.get_in_touch') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('app.get_in_touch') }}</h2>
                
                <div class="space-y-6">
                    @if(\App\Helpers\SettingsHelper::getSchoolAddress())
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('app.address') }}</h3>
                            <p class="text-gray-600">{{ \App\Helpers\SettingsHelper::getSchoolAddress() }}</p>
                        </div>
                    </div>
                    @endif

                    @if(\App\Helpers\SettingsHelper::getSchoolPhone())
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-phone text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('app.phone') }}</h3>
                            <p class="text-gray-600">
                                <a href="tel:{{ \App\Helpers\SettingsHelper::getSchoolPhone() }}" class="hover:text-blue-600">
                                    {{ \App\Helpers\SettingsHelper::getSchoolPhone() }}
                                </a>
                            </p>
                        </div>
                    </div>
                    @endif

                    @if(\App\Helpers\SettingsHelper::getSchoolEmail())
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('app.email') }}</h3>
                            <p class="text-gray-600">
                                <a href="mailto:{{ \App\Helpers\SettingsHelper::getSchoolEmail() }}" class="hover:text-blue-600">
                                    {{ \App\Helpers\SettingsHelper::getSchoolEmail() }}
                                </a>
                            </p>
                        </div>
                    </div>
                    @endif

                    @if(\App\Helpers\SettingsHelper::getSchoolWebsite())
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-globe text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('app.website') }}</h3>
                            <p class="text-gray-600">
                                <a href="{{ \App\Helpers\SettingsHelper::getSchoolWebsite() }}" target="_blank" class="hover:text-blue-600">
                                    {{ \App\Helpers\SettingsHelper::getSchoolWebsite() }}
                                </a>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Office Hours -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.office_hours') }}</h3>
                    <div class="space-y-2 text-gray-600">
                        <div class="flex justify-between">
                            <span>{{ __('app.monday_friday') }}</span>
                            <span>8:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('app.saturday') }}</span>
                            <span>9:00 AM - 1:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('app.sunday') }}</span>
                            <span>{{ __('app.closed') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('app.send_us_message') }}</h2>
                
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('app.name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="Enter your full name"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('app.email') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               placeholder="Enter your email address"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('app.phone') }}
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                               placeholder="Enter your phone number">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-900">
                            Department
                        </label>
                        <select id="department" 
                                name="department"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('department') border-red-500 @enderror">
                            <option value="">Select a department</option>
                            <option value="Admissions" {{ old('department') === 'Admissions' ? 'selected' : '' }}>Admissions</option>
                            <option value="Academic" {{ old('department') === 'Academic' ? 'selected' : '' }}>Academic</option>
                            <option value="Administration" {{ old('department') === 'Administration' ? 'selected' : '' }}>Administration</option>
                            <option value="Student Services" {{ old('department') === 'Student Services' ? 'selected' : '' }}>Student Services</option>
                            <option value="IT Support" {{ old('department') === 'IT Support' ? 'selected' : '' }}>IT Support</option>
                            <option value="General Inquiry" {{ old('department') === 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                        </select>
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('app.subject') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject') border-red-500 @enderror"
                               placeholder="Enter message subject"
                               required>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('app.message') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="5"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('message') border-red-500 @enderror"
                                  placeholder="Enter your message"
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-300">
                            <i class="fas fa-paper-plane mr-2"></i>{{ __('app.send_message') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-public-layout>
