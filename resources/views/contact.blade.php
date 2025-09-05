<x-public-layout>
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="relative h-96 bg-gradient-to-r from-green-600 via-blue-600 to-purple-600 flex items-center justify-center">
            <div class="text-center text-white max-w-4xl px-6">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-2xl">{{ __('app.contact_us') }}</h1>
                <p class="text-xl md:text-2xl drop-shadow-lg">{{ __('app.get_in_touch') }}</p>
            </div>
        </section>

        <!-- Contact Content -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16">
                    <!-- Contact Information -->
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('app.get_in_touch') }}</h2>
                        
                        <div class="space-y-8">
                            <!-- Address -->
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('app.address') }}</h3>
                                    <p class="text-gray-600">123 Education Street, Learning City, LC 12345</p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('app.phone') }}</h3>
                                    <p class="text-gray-600">+1 (555) 123-4567</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('app.email') }}</h3>
                                    <p class="text-gray-600">info@excellenceschool.edu</p>
                                </div>
                            </div>

                            <!-- Office Hours -->
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('app.office_hours') }}</h3>
                                    <p class="text-gray-600">{{ __('app.office_hours_time') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('app.send_us_message') }}</h2>
                        
                        <!-- Success/Error Messages -->
                        @if(session('success'))
                            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.name') }} *</label>
                                    <input type="text" id="contact_name" name="name" value="{{ old('name') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.email') }} *</label>
                                    <input type="email" id="contact_email" name="email" value="{{ old('email') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.phone') }}</label>
                                    <input type="tel" id="contact_phone" name="phone" value="{{ old('phone') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="contact_department" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.department') }}</label>
                                    <select id="contact_department" name="department"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('department') border-red-500 @enderror">
                                        <option value="">{{ __('app.select_department') }}</option>
                                        <option value="general" {{ old('department') == 'general' ? 'selected' : '' }}>{{ __('app.general_inquiry') }}</option>
                                        <option value="admissions" {{ old('department') == 'admissions' ? 'selected' : '' }}>{{ __('app.admissions') }}</option>
                                        <option value="academic" {{ old('department') == 'academic' ? 'selected' : '' }}>{{ __('app.academic_affairs') }}</option>
                                        <option value="administration" {{ old('department') == 'administration' ? 'selected' : '' }}>{{ __('app.administration') }}</option>
                                        <option value="support" {{ old('department') == 'support' ? 'selected' : '' }}>{{ __('app.technical_support') }}</option>
                                    </select>
                                    @error('department')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.subject') }} *</label>
                                <input type="text" id="contact_subject" name="subject" value="{{ old('subject') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject') border-red-500 @enderror">
                                @error('subject')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.message') }} *</label>
                                <textarea id="contact_message" name="message" rows="5" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                {{ __('app.send_message') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-public-layout>
