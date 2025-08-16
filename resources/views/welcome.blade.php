<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@schoolName</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Top Bar -->
        <div class="bg-blue-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-2 text-sm">
                    <div class="flex items-center space-x-4">
                        @if(\App\Helpers\SettingsHelper::getSchoolPhone())
                            <span><i class="fas fa-phone mr-1"></i> {{ \App\Helpers\SettingsHelper::getSchoolPhone() }}</span>
                        @endif
                        @if(\App\Helpers\SettingsHelper::getSchoolEmail())
                            <span><i class="fas fa-envelope mr-1"></i> {{ \App\Helpers\SettingsHelper::getSchoolEmail() }}</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('school.dashboard') }}" class="hover:text-blue-200">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="hover:text-blue-200">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-blue-200">Login</a>
                            <a href="{{ route('register') }}" class="hover:text-blue-200">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="bg-white shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="h-12 w-auto mr-3">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">@schoolName</h1>
                            <p class="text-sm text-gray-600">{{ \App\Helpers\SettingsHelper::getSchoolMotto() ?: 'Nurturing Minds, Building Futures' }}</p>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#home" class="text-gray-900 hover:text-blue-600 font-medium">Home</a>
                        <a href="#about" class="text-gray-900 hover:text-blue-600 font-medium">About</a>
                        <a href="#academics" class="text-gray-900 hover:text-blue-600 font-medium">Academics</a>
                        <a href="#activities" class="text-gray-900 hover:text-blue-600 font-medium">Activities</a>
                        <a href="#announcements" class="text-gray-900 hover:text-blue-600 font-medium">News</a>
                        <a href="#contact" class="text-gray-900 hover:text-blue-600 font-medium">Contact</a>
                    </div>
                    <div class="md:hidden">
                        <button type="button" class="text-gray-900 hover:text-blue-600" onclick="toggleMobileMenu()">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="mobile-menu" class="hidden md:hidden pb-4">
                    <div class="flex flex-col space-y-2">
                        <a href="#home" class="text-gray-900 hover:text-blue-600 font-medium py-2">Home</a>
                        <a href="#about" class="text-gray-900 hover:text-blue-600 font-medium py-2">About</a>
                        <a href="#academics" class="text-gray-900 hover:text-blue-600 font-medium py-2">Academics</a>
                        <a href="#activities" class="text-gray-900 hover:text-blue-600 font-medium py-2">Activities</a>
                        <a href="#announcements" class="text-gray-900 hover:text-blue-600 font-medium py-2">News</a>
                        <a href="#contact" class="text-gray-900 hover:text-blue-600 font-medium py-2">Contact</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Carousel -->
        <section id="home" class="relative">
            <div class="carousel relative h-96 md:h-[500px] overflow-hidden">
                <!-- Slide 1 -->
                <div class="carousel-slide absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/header-bg.jpg') }}')">
                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-center h-full">
                        <div class="text-center text-white">
                            <h1 class="text-4xl md:text-6xl font-bold mb-4">Welcome to Excellence School</h1>
                            <p class="text-xl md:text-2xl mb-8">Where Learning Meets Innovation</p>
                            <a href="#about" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-slide absolute inset-0 bg-cover bg-center opacity-0" style="background-image: url('{{ asset('images/chalkboard.jpg') }}')">
                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-center h-full">
                        <div class="text-center text-white">
                            <h1 class="text-4xl md:text-6xl font-bold mb-4">Excellence in Education</h1>
                            <p class="text-xl md:text-2xl mb-8">Preparing Students for Tomorrow's Challenges</p>
                            <a href="#academics" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                                Our Programs
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-slide absolute inset-0 bg-cover bg-center opacity-0" style="background-image: url('{{ asset('images/footer-bg.jpg') }}')">
                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                    <div class="relative z-10 flex items-center justify-center h-full">
                        <div class="text-center text-white">
                            <h1 class="text-4xl md:text-6xl font-bold mb-4">Building Character</h1>
                            <p class="text-xl md:text-2xl mb-8">Academic Excellence & Personal Growth</p>
                            <a href="#activities" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                                Student Life
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Carousel Controls -->
                <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 p-2 rounded-full transition duration-300" onclick="changeSlide(-1)">
                    <i class="fas fa-chevron-left text-gray-800"></i>
                </button>
                <button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 p-2 rounded-full transition duration-300" onclick="changeSlide(1)">
                    <i class="fas fa-chevron-right text-gray-800"></i>
                </button>

                <!-- Carousel Indicators -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                    <button class="w-3 h-3 bg-white rounded-full opacity-100" onclick="goToSlide(0)"></button>
                    <button class="w-3 h-3 bg-white rounded-full opacity-50" onclick="goToSlide(1)"></button>
                    <button class="w-3 h-3 bg-white rounded-full opacity-50" onclick="goToSlide(2)"></button>
                </div>
            </div>
        </section>

        <!-- Quick Stats -->
        <section class="bg-blue-600 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold mb-2">500+</div>
                        <div class="text-blue-100">Students</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold mb-2">50+</div>
                        <div class="text-blue-100">Teachers</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold mb-2">25+</div>
                        <div class="text-blue-100">Years</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold mb-2">95%</div>
                        <div class="text-blue-100">Success Rate</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">About Our School</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Excellence School has been providing quality education for over 25 years, 
                        fostering academic excellence and character development in a nurturing environment.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Head Teacher -->
                    <div class="text-center">
                        <div class="mb-6">
                            <div class="w-48 h-48 mx-auto bg-gray-300 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-user-tie text-6xl text-gray-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Dr. Sarah Johnson</h3>
                            <p class="text-blue-600 font-semibold mb-4">Head Teacher</p>
                        </div>
                        <div class="text-gray-600 text-left">
                            <p class="mb-4">
                                "At Excellence School, we believe in nurturing not just academic excellence, 
                                but also character, creativity, and critical thinking. Our dedicated team of 
                                educators works tirelessly to provide a holistic learning experience that 
                                prepares students for the challenges of tomorrow."
                            </p>
                            <p>
                                "We are committed to creating an inclusive environment where every student 
                                can thrive and reach their full potential. Our focus on innovation and 
                                personalized learning ensures that each child receives the attention they deserve."
                            </p>
                        </div>
                    </div>

                    <!-- Chairman -->
                    <div class="text-center">
                        <div class="mb-6">
                            <div class="w-48 h-48 mx-auto bg-gray-300 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-user text-6xl text-gray-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Mr. Robert Chen</h3>
                            <p class="text-blue-600 font-semibold mb-4">School Chairman</p>
                        </div>
                        <div class="text-gray-600 text-left">
                            <p class="mb-4">
                                "Our school's mission is to develop well-rounded individuals who are not 
                                only academically proficient but also socially responsible and globally aware. 
                                We invest in modern facilities and technology to provide the best learning environment."
                            </p>
                            <p>
                                "The success of our students is our greatest achievement. We take pride in 
                                our alumni who have gone on to make significant contributions to society. 
                                This motivates us to continue our commitment to educational excellence."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Announcements Section -->
        <section id="announcements" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Latest News & Announcements</h2>
                    <p class="text-xl text-gray-600">Stay updated with the latest happenings at our school</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php
                        $announcements = App\Models\Announcement::active()
                            ->where('target_audience', 'all')
                            ->orderBy('created_at', 'desc')
                            ->limit(6)
                            ->get();
                    @endphp

                    @forelse($announcements as $announcement)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->getPriorityBadgeClass() }}">
                                    {{ ucfirst($announcement->priority) }}
                                </span>
                                <span class="ml-2 text-sm text-gray-500">{{ $announcement->created_at->format('M d, Y') }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $announcement->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($announcement->content, 120) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">By {{ $announcement->createdBy->name }}</span>
                                @if($announcement->hasAttachment())
                                <a href="{{ route('announcements.download', $announcement) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-paperclip mr-1"></i>Attachment
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-newspaper text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">No announcements available at the moment.</p>
                    </div>
                    @endforelse
                </div>

                @if($announcements->count() > 0)
                <div class="text-center mt-8">
                    <a href="{{ route('announcements.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                        View All Announcements
                    </a>
                </div>
                @endif
            </div>
        </section>

        <!-- Academics Section -->
        <section id="academics" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Academic Excellence</h2>
                    <p class="text-xl text-gray-600">Comprehensive programs designed for student success</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-graduation-cap text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Primary Education</h3>
                        <p class="text-gray-600">Grades 1-5 with focus on foundational skills and character development.</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Middle School</h3>
                        <p class="text-gray-600">Grades 6-8 emphasizing critical thinking and subject specialization.</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-microscope text-2xl text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">High School</h3>
                        <p class="text-gray-600">Grades 9-12 with advanced courses and college preparation.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Activities Section -->
        <section id="activities" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">School Activities</h2>
                    <p class="text-xl text-gray-600">Enriching experiences beyond the classroom</p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <!-- School Activities -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Academic Activities</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <i class="fas fa-trophy text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Science Fair</h4>
                                    <p class="text-gray-600">Annual showcase of student scientific projects and innovations.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-pen text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Essay Competition</h4>
                                    <p class="text-gray-600">Creative writing contests to develop communication skills.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-calculator text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Math Olympiad</h4>
                                    <p class="text-gray-600">Competitive mathematics challenges for problem-solving skills.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-language text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Debate Club</h4>
                                    <p class="text-gray-600">Public speaking and critical thinking development.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Co-curricular Activities -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Co-curricular Activities</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <i class="fas fa-music text-green-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Music & Arts</h4>
                                    <p class="text-gray-600">Choir, band, painting, and creative arts programs.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-futbol text-green-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Sports</h4>
                                    <p class="text-gray-600">Football, basketball, swimming, and athletics teams.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-robot text-green-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Robotics Club</h4>
                                    <p class="text-gray-600">STEM education through hands-on robotics projects.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-hands-helping text-green-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Community Service</h4>
                                    <p class="text-gray-600">Volunteer programs and social responsibility initiatives.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Calendar Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Monthly Calendar</h2>
                    <p class="text-xl text-gray-600">Important dates and upcoming events</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">December 2024</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">15</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Winter Concert</h4>
                                    <p class="text-sm text-gray-600">Annual music performance</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">20</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Parent-Teacher Meeting</h4>
                                    <p class="text-sm text-gray-600">Progress review session</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">25</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Winter Break Begins</h4>
                                    <p class="text-sm text-gray-600">School closed for holidays</p>
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
                                    <h4 class="font-semibold text-gray-900">School Reopens</h4>
                                    <p class="text-sm text-gray-600">New semester begins</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">15</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Science Fair</h4>
                                    <p class="text-sm text-gray-600">Student project showcase</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">25</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Sports Day</h4>
                                    <p class="text-sm text-gray-600">Annual athletic competition</p>
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
                                    <h4 class="font-semibold text-gray-900">Art Exhibition</h4>
                                    <p class="text-sm text-gray-600">Student artwork display</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">12</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Debate Competition</h4>
                                    <p class="text-sm text-gray-600">Inter-school debate</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">20</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Career Day</h4>
                                    <p class="text-sm text-gray-600">Professional guidance session</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Useful Links Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Useful Links</h2>
                    <p class="text-xl text-gray-600">Quick access to important resources</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-white rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300">
                        <i class="fas fa-calendar-alt text-3xl text-blue-600 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Academic Calendar</h3>
                        <p class="text-gray-600 mb-4">Important dates and holidays</p>
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">View Calendar</a>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300">
                        <i class="fas fa-book text-3xl text-green-600 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Library</h3>
                        <p class="text-gray-600 mb-4">Online library resources</p>
                        <a href="#" class="text-green-600 hover:text-green-800 font-semibold">Access Library</a>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300">
                        <i class="fas fa-bus text-3xl text-purple-600 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Transportation</h3>
                        <p class="text-gray-600 mb-4">Bus routes and schedules</p>
                        <a href="#" class="text-purple-600 hover:text-purple-800 font-semibold">View Routes</a>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300">
                        <i class="fas fa-utensils text-3xl text-orange-600 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Cafeteria</h3>
                        <p class="text-gray-600 mb-4">Lunch menu and nutrition</p>
                        <a href="#" class="text-orange-600 hover:text-orange-800 font-semibold">View Menu</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Contact Us</h2>
                    <p class="text-xl text-gray-600">Get in touch with us</p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Get In Touch</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-blue-600 mt-1 mr-3 text-xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Address</h4>
                                    <p class="text-gray-600">123 Education Street, Learning City, LC 12345</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-phone text-blue-600 mt-1 mr-3 text-xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Phone</h4>
                                    <p class="text-gray-600">+1 (555) 123-4567</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-envelope text-blue-600 mt-1 mr-3 text-xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Email</h4>
                                    <p class="text-gray-600">info@excellenceschool.edu</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-clock text-blue-600 mt-1 mr-3 text-xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Office Hours</h4>
                                    <p class="text-gray-600">Monday - Friday: 8:00 AM - 4:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>
                        <form class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="h-8 w-auto mr-3">
                            <h3 class="text-xl font-bold">Excellence School</h3>
                        </div>
                        <p class="text-gray-400 mb-4">
                            Nurturing minds and building futures through quality education and character development.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook text-xl"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin text-xl"></i></a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#about" class="text-gray-400 hover:text-white">About Us</a></li>
                            <li><a href="#academics" class="text-gray-400 hover:text-white">Academics</a></li>
                            <li><a href="#activities" class="text-gray-400 hover:text-white">Activities</a></li>
                            <li><a href="#announcements" class="text-gray-400 hover:text-white">News</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4">Resources</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white">Parent Portal</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Student Portal</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Library</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Calendar</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><i class="fas fa-map-marker-alt mr-2"></i>123 Education Street</li>
                            <li><i class="fas fa-phone mr-2"></i>+1 (555) 123-4567</li>
                            <li><i class="fas fa-envelope mr-2"></i>info@excellenceschool.edu</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                    <p class="text-gray-400">&copy; {{ date('Y') }} Excellence School. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.carousel .absolute.bottom-4 button');

        function showSlide(n) {
            slides.forEach((slide, index) => {
                slide.style.opacity = index === n ? '1' : '0';
            });
            
            indicators.forEach((indicator, index) => {
                indicator.style.opacity = index === n ? '1' : '0.5';
            });
        }

        function changeSlide(direction) {
            currentSlide = (currentSlide + direction + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        function goToSlide(n) {
            currentSlide = n;
            showSlide(currentSlide);
        }

        // Auto-advance carousel
        setInterval(() => {
            changeSlide(1);
        }, 5000);

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Smooth scrolling for navigation links
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
    </script>
</body>
</html>
