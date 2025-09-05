<x-public-layout>
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="relative h-96 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 flex items-center justify-center">
            <div class="text-center text-white max-w-4xl px-6">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-2xl">{{ __('app.about_our_school') }}</h1>
                <p class="text-xl md:text-2xl drop-shadow-lg">{{ __('app.about_school_description') }}</p>
            </div>
        </section>

        <!-- About Content -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- School History -->
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-8">{{ __('app.our_history') }}</h2>
                        <div class="space-y-6 text-lg text-gray-600 leading-relaxed">
                            <p>{{ __('app.about_school_description') }}</p>
                            <p>{{ __('app.school_history_paragraph_2') }}</p>
                            <p>{{ __('app.school_history_paragraph_3') }}</p>
                        </div>
                    </div>

                    <!-- School Image -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-3xl p-8 h-96 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-32 h-32 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <h3 class="text-2xl font-bold text-gray-900">{{ __('app.school_building') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">{{ __('app.mission_vision') }}</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ __('app.our_commitment') }}</p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Mission -->
                    <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('app.our_mission') }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ __('app.mission_statement') }}</p>
                    </div>

                    <!-- Vision -->
                    <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300">
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('app.our_vision') }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ __('app.vision_statement') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Leadership Team -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">{{ __('app.leadership_team') }}</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ __('app.meet_our_leaders') }}</p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Head Teacher -->
                    <div class="text-center group">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl p-8 hover:shadow-2xl transition-all duration-500 group-hover:scale-105">
                            <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform duration-500">
                                <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('app.head_teacher_name') }}</h3>
                            <p class="text-blue-600 font-semibold text-lg mb-4">{{ __('app.head_teacher') }}</p>
                            <div class="text-gray-700 space-y-4">
                                <blockquote class="text-lg italic leading-relaxed">
                                    "{{ __('app.head_teacher_quote_1') }}"
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <!-- Chairman -->
                    <div class="text-center group">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-3xl p-8 hover:shadow-2xl transition-all duration-500 group-hover:scale-105">
                            <div class="w-32 h-32 mx-auto bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform duration-500">
                                <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('app.chairman_name') }}</h3>
                            <p class="text-green-600 font-semibold text-lg mb-4">{{ __('app.school_chairman') }}</p>
                            <div class="text-gray-700 space-y-4">
                                <blockquote class="text-lg italic leading-relaxed">
                                    "{{ __('app.chairman_quote_1') }}"
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Teachers -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">{{ __('app.teachers') }}</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ __('app.manage_school_teachers') }}</p>
                </div>

                <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @forelse($teachers as $teacher)
                        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-6 text-center">
                            <div class="w-24 h-24 rounded-full overflow-hidden mx-auto mb-4 bg-gray-100">
                                <img src="{{ $teacher->user->profile_image_url }}" alt="{{ $teacher->full_name }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $teacher->full_name }}</h3>
                            <p class="text-blue-600 text-sm">{{ $teacher->designation ?: ($teacher->qualification ?: __('app.teacher')) }}</p>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 w-full">{{ __('app.no_teachers_found') }}</p>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
</x-public-layout>
