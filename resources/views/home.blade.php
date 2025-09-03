<x-public-layout>
    <main class="py-8">
        <!-- Dynamic Carousel Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div id="home-carousel" class="relative h-96 md:h-[500px] overflow-hidden rounded-lg shadow-lg">
                <!-- Carousel slides will be loaded here dynamically -->
                <div id="carousel-slides" class="relative h-full">
                    <!-- Loading state -->
                    <div class="absolute inset-0 bg-gray-200 flex items-center justify-center">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                            <p class="text-gray-600">{{ __('app.loading_carousel') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Carousel Controls -->
                <button id="carousel-prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-800 p-2 rounded-full shadow-lg transition-all duration-200 z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <button id="carousel-next" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-800 p-2 rounded-full shadow-lg transition-all duration-200 z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Carousel Indicators -->
                <div id="carousel-indicators" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                    <!-- Indicators will be generated dynamically -->
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-blue-600 text-white py-12 rounded-lg">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold mb-2">500+</div>
                        <div class="text-blue-100">{{ __('app.students') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold mb-2">50+</div>
                        <div class="text-blue-100">{{ __('app.teachers') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold mb-2">25+</div>
                        <div class="text-blue-100">{{ __('app.years') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold mb-2">95%</div>
                        <div class="text-blue-100">{{ __('app.success_rate') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('app.about_our_school') }}</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        {{ __('app.about_school_description') }}
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Head Teacher -->
                    <div class="text-center">
                        <div class="mb-6">
                            <div class="w-48 h-48 mx-auto bg-gray-300 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-24 w-24 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Dr. Sarah Johnson</h3>
                            <p class="text-blue-600 font-semibold mb-4">{{ __('app.head_teacher') }}</p>
                        </div>
                        <div class="text-gray-600 text-left">
                            <p class="mb-4">
                                "{{ __('app.head_teacher_quote_1') }}"
                            </p>
                            <p>
                                "{{ __('app.head_teacher_quote_2') }}"
                            </p>
                        </div>
                    </div>

                    <!-- Chairman -->
                    <div class="text-center">
                        <div class="mb-6">
                            <div class="w-48 h-48 mx-auto bg-gray-300 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-24 w-24 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Mr. Robert Chen</h3>
                            <p class="text-blue-600 font-semibold mb-4">{{ __('app.school_chairman') }}</p>
                        </div>
                        <div class="text-gray-600 text-left">
                            <p class="mb-4">
                                "{{ __('app.chairman_quote_1') }}"
                            </p>
                            <p>
                                "{{ __('app.chairman_quote_2') }}"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Announcements Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Latest Announcements -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Latest Announcements
                    </h2>
                    
                    @php
                        $latestAnnouncements = \App\Models\Announcement::with(['createdBy', 'class'])
                            ->where('target_audience', 'all')
                            ->where('is_published', true)
                            ->where(function($q) {
                                $q->whereNull('publish_date')
                                  ->orWhere('publish_date', '<=', now());
                            })
                            ->where(function($q) {
                                $q->whereNull('expire_date')
                                  ->orWhere('expire_date', '>=', now());
                            })
                            ->orderBy('priority', 'asc')
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp
                    
                    @if($latestAnnouncements->count() > 0)
                        <div class="space-y-4">
                            @foreach($latestAnnouncements as $announcement)
                                <div class="border-b border-gray-100 pb-4 last:border-b-0">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('announcements.public.show', $announcement) }}" 
                                           class="hover:text-blue-600 transition-colors">
                                            {{ Str::limit($announcement->title, 50) }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center text-xs text-gray-500 space-x-3 mb-2">
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $announcement->created_at->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $announcement->createdBy->name }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @if($announcement->priority === 'urgent')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                🚨 URGENT
                                            </span>
                                        @elseif($announcement->priority === 'high')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                ⚠️ HIGH
                                            </span>
                                        @elseif($announcement->priority === 'medium')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                📢 MEDIUM
                                            </span>
                                        @endif
                                        @if($announcement->created_at->diffInDays(now()) <= 3)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                🆕 NEW
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 text-sm">No announcements available at the moment.</p>
                    @endif
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('announcements.public') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            View All Announcements
                        </a>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Upcoming Events
                    </h2>
                    <div class="space-y-4">
                        <div class="border-b border-gray-100 pb-4 last:border-b-0">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">
                                <a href="#" class="hover:text-blue-600 transition-colors">Annual Sports Day</a>
                            </h3>
                            <div class="flex items-center text-xs text-gray-500 space-x-3">
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    15 Dec 2024
                                </span>
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    School Ground
                                </span>
                            </div>
                        </div>
                        <div class="border-b border-gray-100 pb-4 last:border-b-0">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">
                                <a href="#" class="hover:text-blue-600 transition-colors">Science Fair Exhibition</a>
                            </h3>
                            <div class="flex items-center text-xs text-gray-500 space-x-3">
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    20 Dec 2024
                                </span>
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Science Lab
                                </span>
                            </div>
                        </div>
                        <div class="border-b border-gray-100 pb-4 last:border-b-0">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">
                                <a href="#" class="hover:text-blue-600 transition-colors">Parent-Teacher Meeting</a>
                            </h3>
                            <div class="flex items-center text-xs text-gray-500 space-x-3">
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    25 Dec 2024
                                </span>
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Auditorium
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 text-center">
                        <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            See More Events
                        </a>
                    </div>
                </div>

                <!-- Courses -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-purple-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                        </svg>
                        Featured Courses
                    </h2>
                    <div class="space-y-4">
                        <div class="border-b border-gray-100 pb-4 last:border-b-0">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">
                                <a href="#" class="hover:text-blue-600 transition-colors">Advanced Mathematics</a>
                            </h3>
                            <div class="flex items-center text-xs text-gray-500 space-x-3">
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    2:00 PM - 3:30 PM
                                </span>
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    Free
                                </span>
                            </div>
                        </div>
                        <div class="border-b border-gray-100 pb-4 last:border-b-0">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">
                                <a href="#" class="hover:text-blue-600 transition-colors">Computer Science Basics</a>
                            </h3>
                            <div class="flex items-center text-xs text-gray-500 space-x-3">
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    4:00 PM - 5:30 PM
                                </span>
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    Free
                                </span>
                            </div>
                        </div>
                        <div class="border-b border-gray-100 pb-4 last:border-b-0">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">
                                <a href="#" class="hover:text-blue-600 transition-colors">Art & Creativity</a>
                            </h3>
                            <div class="flex items-center text-xs text-gray-500 space-x-3">
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    10:00 AM - 11:30 AM
                                </span>
                                <span class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    Free
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 text-center">
                        <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            See More Courses
                        </a>
                    </div>
                </div>
            </div>
        </div>                

        <!-- Calendar Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('app.monthly_calendar') }}</h2>
                    <p class="text-xl text-gray-600">{{ __('app.important_dates') }}</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">December 2024</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">15</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.winter_concert') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.annual_music_performance') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">20</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.parent_teacher_meeting') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.progress_review_session') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">25</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.winter_break_begins') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.school_closed_holidays') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">January 2025</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">8</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.school_reopens') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.new_semester_begins') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">15</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.science_fair') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.science_fair_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">25</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.sports_day') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.annual_athletic_competition') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">February 2025</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.art_exhibition') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.student_artwork_display') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">12</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.debate_competition') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.inter_school_debate') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">20</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.career_day') }}</h4>
                                    <p class="text-sm text-gray-600">{{ __('app.professional_guidance_session') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('app.school_activities') }}</h2>
                    <p class="text-xl text-gray-600">{{ __('app.enriching_experiences') }}</p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <!-- School Activities -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('app.academic_activities') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-blue-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.science_fair') }}</h4>
                                    <p class="text-gray-600">{{ __('app.science_fair_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-blue-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.essay_competition') }}</h4>
                                    <p class="text-gray-600">{{ __('app.essay_competition_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-blue-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.math_olympiad') }}</h4>
                                    <p class="text-gray-600">{{ __('app.math_olympiad_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-blue-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.debate_club') }}</h4>
                                    <p class="text-gray-600">{{ __('app.debate_club_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Co-curricular Activities -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('app.co_curricular_activities') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.music_arts') }}</h4>
                                    <p class="text-gray-600">{{ __('app.music_arts_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m2 0h5m-9 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.sports') }}</h4>
                                    <p class="text-gray-600">{{ __('app.sports_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.robotics_club') }}</h4>
                                    <p class="text-gray-600">{{ __('app.robotics_club_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.community_service') }}</h4>
                                    <p class="text-gray-600">{{ __('app.community_service_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academics Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-gray-50 rounded-lg p-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('app.academic_excellence') }}</h2>
                    <p class="text-xl text-gray-600">{{ __('app.comprehensive_programs') }}</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg p-6 text-center shadow-sm">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('app.primary_education') }}</h3>
                        <p class="text-gray-600">{{ __('app.primary_education_desc') }}</p>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-sm">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('app.middle_school') }}</h3>
                        <p class="text-gray-600">{{ __('app.middle_school_desc') }}</p>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-sm">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('app.high_school') }}</h3>
                        <p class="text-gray-600">{{ __('app.high_school_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>        

        <!-- Contact Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('app.contact_us') }}</h2>
                    <p class="text-xl text-gray-600">{{ __('app.get_in_touch') }}</p>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('app.get_in_touch') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-blue-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.address') }}</h4>
                                    <p class="text-gray-600">123 Education Street, Learning City, LC 12345</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-blue-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.phone') }}</h4>
                                    <p class="text-gray-600">+1 (555) 123-4567</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-blue-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.email') }}</h4>
                                    <p class="text-gray-600">info@excellenceschool.edu</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-blue-600 mt-1 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ __('app.office_hours') }}</h4>
                                    <p class="text-gray-600">Monday - Friday: 8:00 AM - 4:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('app.send_us_message') }}</h3>
                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.name') }}</label>
                                <input type="text" id="contact_name" name="name" value="{{ old('name') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.email') }}</label>
                                <input type="email" id="contact_email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.phone') }}</label>
                                <input type="tel" id="contact_phone" name="phone" value="{{ old('phone') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.subject') }}</label>
                                <input type="text" id="contact_subject" name="subject" value="{{ old('subject') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror">
                                @error('subject')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.message') }}</label>
                                <textarea id="contact_message" name="message" rows="4" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_department" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.department') }}</label>
                                <select id="contact_department" name="department"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('department') border-red-500 @enderror">
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
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                                {{ __('app.send_message') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Useful Links Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-gray-50 rounded-lg p-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('app.useful_links') }}</h2>
                    <p class="text-xl text-gray-600">{{ __('app.quick_access') }}</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-lg transition duration-300">
                        <svg class="h-12 w-12 text-blue-600 mb-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('app.academic_calendar') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('app.important_dates_holidays') }}</p>
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">{{ __('app.view_calendar') }}</a>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-lg transition duration-300">
                        <svg class="h-12 w-12 text-green-600 mb-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('app.library') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('app.online_library_resources') }}</p>
                        <a href="#" class="text-green-600 hover:text-green-800 font-semibold">{{ __('app.access_library') }}</a>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-lg transition duration-300">
                        <svg class="h-12 w-12 text-purple-600 mb-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('app.transportation') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('app.bus_routes_schedules') }}</p>
                        <a href="#" class="text-purple-600 hover:text-purple-800 font-semibold">{{ __('app.view_routes') }}</a>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-sm hover:shadow-lg transition duration-300">
                        <svg class="h-12 w-12 text-orange-600 mb-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('app.cafeteria') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('app.lunch_menu_nutrition') }}</p>
                        <a href="#" class="text-orange-600 hover:text-orange-800 font-semibold">{{ __('app.view_menu') }}</a>
                    </div>
                </div>
            </div>
        </div>                

        <!-- Carousel JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let currentSlide = 0;
                let slides = [];
                let autoPlayInterval;

                // Load carousel slides from the API
                async function loadCarouselSlides() {
                    try {
                        const response = await fetch('/api/carousel/active');
                        if (!response.ok) {
                            throw new Error('Failed to load carousel slides');
                        }
                        
                        slides = await response.json();
                        
                        if (slides.length === 0) {
                            // No slides available, show default message
                            document.getElementById('carousel-slides').innerHTML = `
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <h2 class="text-3xl font-bold mb-4">{{ __('app.welcome_to_school') }}</h2>
                                        <p class="text-xl">{{ __('app.discover_excellence') }}</p>
                                    </div>
                                </div>
                            `;
                            return;
                        }

                        renderCarousel();
                        startAutoPlay();
                    } catch (error) {
                        console.error('Error loading carousel slides:', error);
                        // Show error state
                        document.getElementById('carousel-slides').innerHTML = `
                            <div class="absolute inset-0 bg-gray-200 flex items-center justify-center">
                                <div class="text-center text-gray-600">
                                    <p>{{ __('app.unable_to_load_carousel') }}</p>
                                </div>
                            </div>
                        `;
                    }
                }

                // Render the carousel with slides
                function renderCarousel() {
                    const slidesContainer = document.getElementById('carousel-slides');
                    const indicatorsContainer = document.getElementById('carousel-indicators');
                    
                    // Clear existing content
                    slidesContainer.innerHTML = '';
                    indicatorsContainer.innerHTML = '';

                    slides.forEach((slide, index) => {
                        // Create slide element
                        const slideElement = document.createElement('div');
                        slideElement.className = `carousel-slide absolute inset-0 bg-cover bg-center transition-opacity duration-500 ${index === 0 ? 'opacity-100' : 'opacity-0'}`;
                        slideElement.style.backgroundImage = `url('${slide.image_url}')`;
                        
                        // Add slide content overlay
                        slideElement.innerHTML = `
                            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white max-w-4xl px-6">
                                    ${slide.title ? `<h2 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow-lg">${slide.title}</h2>` : ''}
                                    ${slide.subtitle ? `<h3 class="text-xl md:text-2xl mb-4 drop-shadow-lg">${slide.subtitle}</h3>` : ''}
                                    ${slide.description ? `<p class="text-lg md:text-xl mb-6 drop-shadow-lg">${slide.description}</p>` : ''}
                                    ${slide.button_text && slide.button_url ? `<a href="${slide.button_url}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-semibold transition-colors duration-200 drop-shadow-lg">${slide.button_text}</a>` : ''}
                                </div>
                            </div>
                        `;
                        
                        slidesContainer.appendChild(slideElement);

                        // Create indicator
                        const indicator = document.createElement('button');
                        indicator.className = `w-3 h-3 rounded-full transition-all duration-200 ${index === 0 ? 'bg-white' : 'bg-white bg-opacity-50'}`;
                        indicator.onclick = () => goToSlide(index);
                        indicatorsContainer.appendChild(indicator);
                    });
                }

                // Go to specific slide
                function goToSlide(index) {
                    if (index < 0 || index >= slides.length) return;
                    
                    const slideElements = document.querySelectorAll('.carousel-slide');
                    const indicators = document.querySelectorAll('#carousel-indicators button');
                    
                    // Hide all slides
                    slideElements.forEach(slide => slide.classList.add('opacity-0'));
                    slideElements.forEach(slide => slide.classList.remove('opacity-100'));
                    
                    // Show current slide
                    slideElements[index].classList.remove('opacity-0');
                    slideElements[index].classList.add('opacity-100');
                    
                    // Update indicators
                    indicators.forEach((indicator, i) => {
                        if (i === index) {
                            indicator.classList.remove('bg-opacity-50');
                            indicator.classList.add('bg-white');
                        } else {
                            indicator.classList.add('bg-opacity-50');
                            indicator.classList.remove('bg-white');
                        }
                    });
                    
                    currentSlide = index;
                }

                // Next slide
                function nextSlide() {
                    const nextIndex = (currentSlide + 1) % slides.length;
                    goToSlide(nextIndex);
                }

                // Previous slide
                function prevSlide() {
                    const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
                    goToSlide(prevIndex);
                }

                // Start auto-play
                function startAutoPlay() {
                    if (slides.length <= 1) return;
                    
                    autoPlayInterval = setInterval(() => {
                        nextSlide();
                    }, 5000); // Change slide every 5 seconds
                }

                // Stop auto-play
                function stopAutoPlay() {
                    if (autoPlayInterval) {
                        clearInterval(autoPlayInterval);
                    }
                }

                // Event listeners
                document.getElementById('carousel-next').addEventListener('click', () => {
                    nextSlide();
                    stopAutoPlay();
                    startAutoPlay(); // Restart auto-play
                });

                document.getElementById('carousel-prev').addEventListener('click', () => {
                    prevSlide();
                    stopAutoPlay();
                    startAutoPlay(); // Restart auto-play
                });

                // Pause auto-play on hover
                document.getElementById('home-carousel').addEventListener('mouseenter', stopAutoPlay);
                document.getElementById('home-carousel').addEventListener('mouseleave', startAutoPlay);

                // Load carousel on page load
                loadCarouselSlides();
            });
        </script>
    </main>
</x-public-layout>
