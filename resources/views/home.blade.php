<x-public-layout>
    <main class="min-h-screen">
        <!-- Hero Section with Dynamic Carousel -->
        <section class="relative h-screen overflow-hidden">
            <div id="home-carousel" class="relative h-full">
                <!-- Carousel slides will be loaded here dynamically -->
                <div id="carousel-slides" class="relative h-full">
                    <!-- Loading state -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-purple-900 flex items-center justify-center">
                        <div class="text-center text-white">
                            <div class="animate-spin rounded-full h-16 w-16 border-4 border-white border-t-transparent mx-auto mb-6"></div>
                            <h2 class="text-2xl font-bold mb-2">{{ __('app.loading_carousel') }}</h2>
                            <p class="text-blue-200">{{ __('app.please_wait') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Carousel Controls -->
                <button id="carousel-prev" class="absolute left-6 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm text-white p-3 rounded-full shadow-xl transition-all duration-300 z-20 group">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <button id="carousel-next" class="absolute right-6 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm text-white p-3 rounded-full shadow-xl transition-all duration-300 z-20 group">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Carousel Indicators -->
                <div id="carousel-indicators" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
                    <!-- Indicators will be generated dynamically -->
                </div>

            </div>
        </section>

        <!-- Dynamic Stats Section -->
        <section class="py-20 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></svg>');"></div>
                    </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('app.our_achievements') }}</h2>
                    <p class="text-xl text-blue-100 max-w-3xl mx-auto">{{ __('app.excellence_in_numbers') }}</p>
                    </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @php
                        $stats = [
                            'students' => \App\Models\Student::active()->count(),
                            'teachers' => \App\Models\Teacher::active()->count(),
                            'classes' => \App\Models\SchoolClass::active()->count(),
                            'subjects' => \App\Models\Subject::active()->count(),
                        ];
                    @endphp
                    
                    <div class="text-center group">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-8 hover:bg-opacity-30 transition-all duration-300 group-hover:scale-105">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                    </div>
                            <div class="text-4xl md:text-5xl font-bold text-white mb-2 counter" data-target="{{ $stats['students'] }}">0</div>
                            <div class="text-blue-100 text-lg font-medium">{{ __('app.students') }}</div>
                    </div>
                </div>
                    
                    <div class="text-center group">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-8 hover:bg-opacity-30 transition-all duration-300 group-hover:scale-105">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="text-4xl md:text-5xl font-bold text-white mb-2 counter" data-target="{{ $stats['teachers'] }}">0</div>
                            <div class="text-blue-100 text-lg font-medium">{{ __('app.teachers') }}</div>
            </div>
        </div>
                    
                    <div class="text-center group">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-8 hover:bg-opacity-30 transition-all duration-300 group-hover:scale-105">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div class="text-4xl md:text-5xl font-bold text-white mb-2 counter" data-target="{{ $stats['classes'] }}">0</div>
                            <div class="text-blue-100 text-lg font-medium">{{ __('app.classes') }}</div>
                        </div>
                    </div>
                    
                    <div class="text-center group">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-8 hover:bg-opacity-30 transition-all duration-300 group-hover:scale-105">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                                </svg>
                            </div>
                            <div class="text-4xl md:text-5xl font-bold text-white mb-2 counter" data-target="{{ $stats['subjects'] }}">0</div>
                            <div class="text-blue-100 text-lg font-medium">{{ __('app.subjects') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="py-20 bg-white" id="about">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ \App\Helpers\SettingsHelper::getLocalized('home_about_title', __('app.about_our_school')) }}</h2>
                    <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                        {{ \App\Helpers\SettingsHelper::getLocalized('home_about_description', __('app.about_school_description')) }}
                    </p>
                </div>

                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Head Teacher -->
                    <div class="group">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl p-8 hover:shadow-2xl transition-all duration-500 group-hover:scale-105">
                            <div class="text-center mb-8">
                                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform duration-500">
                                    <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ \App\Helpers\SettingsHelper::getLocalized('head_teacher_name', __('app.head_teacher_name')) }}</h3>
                                <p class="text-blue-600 font-semibold text-lg">{{ \App\Helpers\SettingsHelper::getLocalized('head_teacher_title', __('app.head_teacher')) }}</p>
                        </div>
                            <div class="text-gray-700 space-y-4">
                                <blockquote class="text-lg italic leading-relaxed">
                                "{{ \App\Helpers\SettingsHelper::getLocalized('head_teacher_quote_1', __('app.head_teacher_quote_1')) }}"
                                </blockquote>
                                <blockquote class="text-lg italic leading-relaxed">
                                "{{ \App\Helpers\SettingsHelper::getLocalized('head_teacher_quote_2', __('app.head_teacher_quote_2')) }}"
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <!-- Chairman -->
                    <div class="group">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-3xl p-8 hover:shadow-2xl transition-all duration-500 group-hover:scale-105">
                            <div class="text-center mb-8">
                                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform duration-500">
                                    <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ \App\Helpers\SettingsHelper::getLocalized('chairman_name', __('app.chairman_name')) }}</h3>
                                <p class="text-green-600 font-semibold text-lg">{{ \App\Helpers\SettingsHelper::getLocalized('chairman_title', __('app.school_chairman')) }}</p>
                        </div>
                            <div class="text-gray-700 space-y-4">
                                <blockquote class="text-lg italic leading-relaxed">
                                "{{ \App\Helpers\SettingsHelper::getLocalized('chairman_quote_1', __('app.chairman_quote_1')) }}"
                                </blockquote>
                                <blockquote class="text-lg italic leading-relaxed">
                                "{{ \App\Helpers\SettingsHelper::getLocalized('chairman_quote_2', __('app.chairman_quote_2')) }}"
                                </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>

        <!-- Latest Announcements & Events Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ __('app.latest_news_events') }}</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ __('app.stay_updated_with_latest') }}</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                <!-- Latest Announcements -->
                    <div class="lg:col-span-2 flex flex-col">
                        <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 flex flex-col h-full">
                            <div class="flex items-center mb-8">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ __('app.latest_announcements') }}</h3>
                            </div>
                    
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
                                    ->limit(4)
                            ->get();
                    @endphp
                    
                    @if($latestAnnouncements->count() > 0)
                                <div class="space-y-6">
                            @foreach($latestAnnouncements as $announcement)
                                        <div class="group border-l-4 border-blue-200 pl-6 py-4 hover:border-blue-500 transition-all duration-300">
                                            <div class="flex items-start justify-between mb-3">
                                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                    <a href="{{ route('announcements.public.show', $announcement->id) }}" class="hover:underline">
                                                        {{ Str::limit($announcement->title, 60) }}
                                                    </a>
                                                </h4>
                                                <div class="flex flex-wrap gap-2 ml-4">
                                        @if($announcement->priority === 'urgent')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                                                🚨 URGENT
                                            </span>
                                        @elseif($announcement->priority === 'high')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                ⚠️ HIGH
                                            </span>
                                        @elseif($announcement->priority === 'medium')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                📢 MEDIUM
                                            </span>
                                        @endif
                                        @if($announcement->created_at->diffInDays(now()) <= 3)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                🆕 NEW
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                            
                                            <div class="flex items-center text-sm text-gray-500 space-x-4 mb-3">
                                                <span class="flex items-center">
                                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $announcement->created_at->format('d M Y') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    {{ $announcement->createdBy->name }}
                                                </span>
                        </div>
                                            
                                            @if($announcement->description)
                                                <p class="text-gray-600 text-sm leading-relaxed">
                                                    {{ Str::limit($announcement->description, 120) }}
                                                </p>
                    @endif
                                        </div>
                                    @endforeach
                                </div>
                    
                                <div class="mt-auto pt-8 text-center">
                        <a href="{{ route('announcements.public') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors duration-300 group">
                                        {{ __('app.view_all_announcements') }}
                                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-lg">{{ __('app.no_announcements_available') }}</p>
                                </div>
                            @endif
                    </div>
                </div>

                <!-- Upcoming Events -->
                    <div class="flex flex-col">
                        <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 flex flex-col h-full">
                        <div class="flex items-center mb-8">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ __('app.upcoming_events') }}</h3>
                        </div>
                        
                        @php
                            $colorMap = [
                                'sports' => 'green',
                                'academic' => 'blue',
                                'meeting' => 'purple',
                                'cultural' => 'orange',
                            ];
                        @endphp
                        
                        <div class="space-y-6">
                            @forelse(($events ?? []) as $event)
                                @php $color = $event->color ?: ($colorMap[$event->type] ?? 'green'); @endphp
                                <div class="group border-l-4 border-{{ $color }}-200 pl-6 py-4 hover:border-{{ $color }}-500 transition-all duration-300">
                                    <h4 class="text-lg font-semibold text-gray-900 group-hover:text-{{ $color }}-600 transition-colors mb-3">
                                        {{ $event->title }}
                                    </h4>
                                    
                                    <div class="flex items-center text-sm text-gray-500 space-x-4 mb-3">
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $event->start_at->format('d M Y') }}
                                        </span>
                                        @if($event->location)
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $event->location }}
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center">
                                        @if($event->type)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                            {{ ucfirst($event->type) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500">{{ __('app.no_events_available') }}</div>
                            @endforelse
                        </div>
                        
                            <div class="mt-auto pt-8 text-center">
                                <a href="{{ route('events') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors duration-300 group">
                                    {{ __('app.see_more_events') }}
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                        </a>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </section>

                    <!-- Featured Courses -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center mb-8">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253" />
                        </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ __('app.featured_courses') }}</h3>
                        </div>
                        
                        @php
                            $featuredCourses = [
                                [
                                    'title' => __('app.advanced_mathematics'),
                                    'time' => '2:00 PM - 3:30 PM',
                                    'price' => __('app.free'),
                                    'color' => 'blue',
                                    'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'
                                ],
                                [
                                    'title' => __('app.computer_science_basics'),
                                    'time' => '4:00 PM - 5:30 PM',
                                    'price' => __('app.free'),
                                    'color' => 'green',
                                    'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
                                ],
                                [
                                    'title' => __('app.art_creativity'),
                                    'time' => '10:00 AM - 11:30 AM',
                                    'price' => __('app.free'),
                                    'color' => 'purple',
                                    'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z'
                                ]
                            ];
                        @endphp
                        
                        <div class="space-y-6">
                            @foreach($featuredCourses as $course)
                                <div class="group border-l-4 border-{{ $course['color'] }}-200 pl-6 py-4 hover:border-{{ $course['color'] }}-500 transition-all duration-300">
                                    <div class="flex items-start justify-between mb-3">
                                        <h4 class="text-lg font-semibold text-gray-900 group-hover:text-{{ $course['color'] }}-600 transition-colors">
                                            <a href="#" class="hover:underline">
                                                {{ $course['title'] }}
                                            </a>
                                        </h4>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $course['color'] }}-100 text-{{ $course['color'] }}-800">
                                            {{ $course['price'] }}
                                </span>
                            </div>
                                    
                                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                                <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                            {{ $course['time'] }}
                                </span>
                                <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $course['icon'] }}" />
                                    </svg>
                                            {{ ucfirst($course['color']) }} Course
                                </span>
                            </div>
                        </div>
                            @endforeach
                    </div>
                        
                        <div class="mt-8 text-center">
                            <a href="#" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-xl font-semibold hover:bg-purple-700 transition-colors duration-300 group">
                                {{ __('app.see_more_courses') }}
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>                
        </section>

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
        <section class="relative overflow-hidden mb-12">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 opacity-10 pointer-events-none"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-stretch">
                    <!-- Left: Info Card -->
                    <div class="relative">
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 p-8 h-full">
                            <div class="text-left mb-8">
                                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ __('app.contact_us') }}</h2>
                                <p class="text-lg text-gray-600">{{ __('app.get_in_touch') }}</p>
                            </div>
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mr-4">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ __('app.address') }}</h4>
                                        <p class="text-gray-600">123 Education Street, Learning City, LC 12345</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mr-4">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ __('app.phone') }}</h4>
                                        <p class="text-gray-600">+1 (555) 123-4567</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mr-4">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ __('app.email') }}</h4>
                                        <p class="text-gray-600">info@excellenceschool.edu</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mr-4">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ __('app.office_hours') }}</h4>
                                        <p class="text-gray-600">{{ __('app.office_hours_time') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 grid sm:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-xl p-4 text-center">
                                    <div class="text-2xl font-bold text-gray-900">24/7</div>
                                    <div class="text-gray-500 text-sm">{{ __('app.support') }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4 text-center">
                                    <div class="text-2xl font-bold text-gray-900">1h</div>
                                    <div class="text-gray-500 text-sm">{{ __('app.avg_response_time') }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4 text-center">
                                    <div class="text-2xl font-bold text-gray-900">100%</div>
                                    <div class="text-gray-500 text-sm">{{ __('app.satisfaction') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Modern Form Card -->
                    <div class="relative">
                        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-6 sm:p-8">
                            <!-- Success/Error Messages -->
                            @if(session('success'))
                                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 flex items-start" role="alert">
                                    <svg class="h-5 w-5 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m0 6a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 flex items-start" role="alert">
                                    <svg class="h-5 w-5 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" /></svg>
                                    <span class="block sm:inline">{{ session('error') }}</span>
                                </div>
                            @endif

                            <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('app.send_us_message') }}</h3>
                            <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                                @csrf
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.name') }}</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </span>
                                            <input type="text" id="contact_name" name="name" value="{{ old('name') }}" required
                                                   class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('name') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror">
                                        </div>
                                        @error('name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.email') }}</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                                            </span>
                                            <input type="email" id="contact_email" name="email" value="{{ old('email') }}" required
                                                   class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('email') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror">
                                        </div>
                                        @error('email')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.phone') }} <span class="text-gray-400">({{ __('app.optional') }})</span></label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            </span>
                                            <input type="tel" id="contact_phone" name="phone" value="{{ old('phone') }}"
                                                   class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('phone') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror">
                                        </div>
                                        @error('phone')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="contact_department" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.department') }}</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M6 12h12M9 17h6"/></svg>
                                            </span>
                                            <select id="contact_department" name="department"
                                                    class="w-full pl-10 pr-8 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 appearance-none @error('department') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror">
                                                <option value="">{{ __('app.select_department') }}</option>
                                                <option value="general" {{ old('department') == 'general' ? 'selected' : '' }}>{{ __('app.general_inquiry') }}</option>
                                                <option value="admissions" {{ old('department') == 'admissions' ? 'selected' : '' }}>{{ __('app.admissions') }}</option>
                                                <option value="academic" {{ old('department') == 'academic' ? 'selected' : '' }}>{{ __('app.academic_affairs') }}</option>
                                                <option value="administration" {{ old('department') == 'administration' ? 'selected' : '' }}>{{ __('app.administration') }}</option>
                                                <option value="support" {{ old('department') == 'support' ? 'selected' : '' }}>{{ __('app.technical_support') }}</option>
                                            </select>
                                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </span>
                                        </div>
                                        @error('department')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.subject') }}</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M4 6h16"/></svg>
                                        </span>
                                        <input type="text" id="contact_subject" name="subject" value="{{ old('subject') }}" required
                                               class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('subject') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror">
                                    </div>
                                    @error('subject')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.message') }}</label>
                                    <div class="relative">
                                        <span class="absolute top-3 left-3 text-gray-400">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"/></svg>
                                        </span>
                                        <textarea id="contact_message" name="message" rows="5" required
                                                  class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('message') border-red-300 focus:ring-red-200 focus:border-red-400 @enderror">{{ old('message') }}</textarea>
                                    </div>
                                    @error('message')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <p class="text-sm text-gray-500">{{ __('app.we_respect_privacy') }}</p>
                                    <button type="submit" class="inline-flex items-center px-6 py-3 rounded-xl text-white font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-600/20 transition-all">
                                        {{ __('app.send_message') }}
                                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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

        <!-- Enhanced JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let currentSlide = 0;
                let slides = [];
                let autoPlayInterval;

                // Counter Animation
                function animateCounters() {
                    const counters = document.querySelectorAll('.counter');
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-target'));
                        const duration = 2000; // 2 seconds
                        const increment = target / (duration / 16); // 60fps
                        let current = 0;
                        
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            counter.textContent = Math.floor(current);
                        }, 16);
                    });
                }

                // Intersection Observer for animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-fade-in-up');
                            if (entry.target.classList.contains('counter')) {
                                animateCounters();
                            }
                        }
                    });
                }, observerOptions);

                // Observe all animated elements
                document.querySelectorAll('.counter, .group').forEach(el => {
                    observer.observe(el);
                });

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
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-purple-800 to-indigo-900 flex items-center justify-center">
                                    <div class="text-center text-white max-w-4xl px-6">
                                        <h2 class="text-5xl md:text-7xl font-bold mb-6 drop-shadow-2xl animate-fade-in-up">{{ __('app.welcome_to_school') }}</h2>
                                        <p class="text-2xl md:text-3xl mb-8 drop-shadow-lg animate-fade-in-up delay-300">{{ __('app.discover_excellence') }}</p>
                                        <div class="animate-bounce">
                                            <svg class="w-12 h-12 mx-auto text-white opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                        </div>
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
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                    </div>
                                    <p class="text-xl">{{ __('app.unable_to_load_carousel') }}</p>
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
                        slideElement.className = `carousel-slide absolute inset-0 bg-cover bg-center transition-all duration-1000 ${index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105'}`;
                        slideElement.style.backgroundImage = `url('${slide.image_url}')`;
                        
                        // Add slide content overlay
                        slideElement.innerHTML = `
                            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-black/60"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white max-w-5xl px-6 transform transition-all duration-1000 ${index === 0 ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'}">
                                    ${slide.title ? `<h2 class="text-5xl md:text-7xl font-bold mb-6 drop-shadow-2xl leading-tight">${slide.title}</h2>` : ''}
                                    ${slide.subtitle ? `<h3 class="text-2xl md:text-3xl mb-6 drop-shadow-lg font-light">${slide.subtitle}</h3>` : ''}
                                    ${slide.description ? `<p class="text-lg md:text-xl mb-8 drop-shadow-lg max-w-3xl mx-auto leading-relaxed">${slide.description}</p>` : ''}
                                    ${slide.button_text && slide.button_url ? `
                                        <a href="${slide.button_url}" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-10 py-4 rounded-2xl text-lg font-semibold transition-all duration-300 drop-shadow-lg hover:scale-105 hover:shadow-2xl">
                                            ${slide.button_text}
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                        
                        slidesContainer.appendChild(slideElement);

                        // Create indicator
                        const indicator = document.createElement('button');
                        indicator.className = `w-4 h-4 rounded-full transition-all duration-300 ${index === 0 ? 'bg-white scale-125' : 'bg-white bg-opacity-50 hover:bg-opacity-75'}`;
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
                    slideElements.forEach(slide => {
                        slide.classList.add('opacity-0', 'scale-105');
                        slide.classList.remove('opacity-100', 'scale-100');
                    });
                    
                    // Show current slide
                    slideElements[index].classList.remove('opacity-0', 'scale-105');
                    slideElements[index].classList.add('opacity-100', 'scale-100');
                    
                    // Update indicators
                    indicators.forEach((indicator, i) => {
                        if (i === index) {
                            indicator.classList.remove('bg-opacity-50');
                            indicator.classList.add('bg-white', 'scale-125');
                        } else {
                            indicator.classList.add('bg-opacity-50');
                            indicator.classList.remove('bg-white', 'scale-125');
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
                    }, 6000); // Change slide every 6 seconds
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
                    startAutoPlay();
                });

                document.getElementById('carousel-prev').addEventListener('click', () => {
                    prevSlide();
                    stopAutoPlay();
                    startAutoPlay();
                });

                // Pause auto-play on hover
                document.getElementById('home-carousel').addEventListener('mouseenter', stopAutoPlay);
                document.getElementById('home-carousel').addEventListener('mouseleave', startAutoPlay);

                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') {
                        prevSlide();
                        stopAutoPlay();
                        startAutoPlay();
                    } else if (e.key === 'ArrowRight') {
                        nextSlide();
                        stopAutoPlay();
                        startAutoPlay();
                    } else if (e.key === 'Home' || (e.key === 'ArrowUp' && e.ctrlKey)) {
                        // Home key or Ctrl+Up arrow to go to top
                        e.preventDefault();
                        scrollToTop();
                    }
                });

                // Load carousel on page load
                loadCarouselSlides();

                // Smooth scrolling for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });


                // Back to top functionality
                function scrollToTop() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }

                // Show/hide back to top button based on scroll position
                function handleBackToTopButton() {
                    const backToTopButton = document.getElementById('back-to-top');
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const windowHeight = window.innerHeight;
                    
                    if (scrollTop > windowHeight * 0.5) {
                        backToTopButton.classList.remove('opacity-0', 'invisible');
                        backToTopButton.classList.add('opacity-100', 'visible');
                    } else {
                        backToTopButton.classList.add('opacity-0', 'invisible');
                        backToTopButton.classList.remove('opacity-100', 'visible');
                    }
                }

                // Add click event listener for back to top button
                document.getElementById('back-to-top').addEventListener('click', scrollToTop);

                // Add scroll event listener for back to top button
                window.addEventListener('scroll', handleBackToTopButton);
                
                // Initial check for back to top button
                handleBackToTopButton();
            });
        </script>

        <!-- Custom CSS for animations -->
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fadeInUp 0.8s ease-out forwards;
            }

            .delay-300 {
                animation-delay: 0.3s;
            }

            .delay-600 {
                animation-delay: 0.6s;
            }

            .delay-900 {
                animation-delay: 0.9s;
            }


            /* Back to top button animations */
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideOutDown {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(20px);
                }
            }

            #back-to-top {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            #back-to-top.visible {
                animation: slideInUp 0.3s ease-out;
            }

            #back-to-top.invisible {
                animation: slideOutDown 0.3s ease-in;
            }

            #back-to-top:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
            }

            /* Smooth transitions for all interactive elements */
            * {
                transition: all 0.3s ease;
            }

            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        </style>

        <!-- Back to Top Button -->
        <button id="back-to-top" class="fixed bottom-8 right-8 z-50 bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg transition-all duration-300 opacity-0 invisible group" title="{{ __('app.back_to_top') }}">
            <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        </button>
    </main>
</x-public-layout>
