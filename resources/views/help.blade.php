<x-public-layout>
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="relative h-96 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 flex items-center justify-center">
            <div class="text-center text-white max-w-4xl px-6">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-2xl">{{ __('app.help_center') }}</h1>
                <p class="text-xl md:text-2xl drop-shadow-lg">{{ __('app.find_answers_here') }}</p>
            </div>
        </section>

        <!-- Help Content -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">{{ __('app.frequently_asked_questions') }}</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ __('app.common_questions_answers') }}</p>
                </div>

                <div class="max-w-4xl mx-auto">
                    <div class="space-y-6">
                        <!-- FAQ Item 1 -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300">
                            <button class="w-full px-8 py-6 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('app.how_to_apply') }}</h3>
                                    <svg class="w-6 h-6 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="mt-4 text-gray-600">
                                    <p>{{ __('app.admission_process_description') }}</p>
                                </div>
                            </button>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300">
                            <button class="w-full px-8 py-6 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('app.school_hours') }}</h3>
                                    <svg class="w-6 h-6 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="mt-4 text-gray-600">
                                    <p>{{ __('app.school_hours_description') }}</p>
                                </div>
                            </button>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300">
                            <button class="w-full px-8 py-6 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('app.transportation') }}</h3>
                                    <svg class="w-6 h-6 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="mt-4 text-gray-600">
                                    <p>{{ __('app.transportation_description') }}</p>
                                </div>
                            </button>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300">
                            <button class="w-full px-8 py-6 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('app.contact_teachers') }}</h3>
                                    <svg class="w-6 h-6 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="mt-4 text-gray-600">
                                    <p>{{ __('app.contact_teachers_description') }}</p>
                                </div>
                            </button>
                        </div>

                        <!-- FAQ Item 5 -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300">
                            <button class="w-full px-8 py-6 text-left focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-2xl">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('app.technical_support') }}</h3>
                                    <svg class="w-6 h-6 text-gray-500 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="mt-4 text-gray-600">
                                    <p>{{ __('app.technical_support_description') }}</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Support -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">{{ __('app.still_need_help') }}</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ __('app.contact_support_description') }}</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Email Support -->
                    <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ __('app.email_support') }}</h3>
                        <p class="text-gray-600 mb-6">{{ __('app.email_support_description') }}</p>
                        <a href="mailto:support@excellenceschool.edu" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                            {{ __('app.send_email') }}
                        </a>
                    </div>

                    <!-- Phone Support -->
                    <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ __('app.phone_support') }}</h3>
                        <p class="text-gray-600 mb-6">{{ __('app.phone_support_description') }}</p>
                        <a href="tel:+15551234567" class="inline-block bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 transition-colors">
                            {{ __('app.call_now') }}
                        </a>
                    </div>

                    <!-- Live Chat -->
                    <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ __('app.live_chat') }}</h3>
                        <p class="text-gray-600 mb-6">{{ __('app.live_chat_description') }}</p>
                        <button class="inline-block bg-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                            {{ __('app.start_chat') }}
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-public-layout>
