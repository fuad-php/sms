<x-public-layout>    
    <main class="main-content">
        <div class="fullwidth-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="section-title"><i class="icon-newspaper"></i> Latest Announcements</h2>
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
                            <ul class="posts">
                                @foreach($latestAnnouncements as $announcement)
                                    <li class="post">
                                        <h3 class="entry-title">
                                            <a href="{{ route('announcements.show', $announcement) }}">
                                                {{ Str::limit($announcement->title, 50) }}
                                            </a>
                                        </h3>
                                        <span class="date">
                                            <i class="icon-calendar"></i> 
                                            {{ $announcement->created_at->format('d M Y') }}
                                        </span>
                                        <span class="author">
                                            <i class="icon-user"></i>{{ $announcement->createdBy->name }}
                                        </span>
                                        @if($announcement->priority === 'urgent')
                                            <span class="priority-badge" style="color: #dc2626; font-weight: bold; background: #fef2f2; padding: 2px 6px; border-radius: 4px;">
                                                🚨 URGENT
                                            </span>
                                        @elseif($announcement->priority === 'high')
                                            <span class="priority-badge" style="color: #ea580c; font-weight: bold; background: #fff7ed; padding: 2px 6px; border-radius: 4px;">
                                                ⚠️ HIGH
                                            </span>
                                        @elseif($announcement->priority === 'medium')
                                            <span class="priority-badge" style="color: #ca8a04; font-weight: bold; background: #fefce8; padding: 2px 6px; border-radius: 4px;">
                                                📢 MEDIUM
                                            </span>
                                        @endif
                                        @if($announcement->created_at->diffInDays(now()) <= 3)
                                            <span class="new-badge" style="color: #059669; font-weight: bold; background: #ecfdf5; padding: 2px 6px; border-radius: 4px;">
                                                🆕 NEW
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center text-gray-500">No announcements available at the moment.</p>
                        @endif
                        
                        <p class="text-center">
                            <a href="{{ route('announcements.public') }}" class="more button secondary">View All Announcements</a>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h2 class="section-title"><i class="icon-calendar-lg"></i> Upcoming Events</h2>
                        <ul class="posts">
                            <li class="post">
                                <h3 class="entry-title"><a href="#">Nostrud exercitation ullamco</a></h3>
                                <span class="date"><i class="icon-calendar"></i> 6 APR 2014</span>
                                <span class="location"><i class="icon-map-marker"></i>Yankee Stadium</span>
                            </li>
                            <li class="post">
                                <h3 class="entry-title"><a href="#">Nostrud exercitation ullamco</a></h3>
                                <span class="date"><i class="icon-calendar"></i> 6 APR 2014</span>
                                <span class="location"><i class="icon-map-marker"></i>Yankee Stadium</span>
                            </li>
                            <li class="post">
                                <h3 class="entry-title"><a href="#">Nostrud exercitation ullamco</a></h3>
                                <span class="date"><i class="icon-calendar"></i> 6 APR 2014</span>
                                <span class="location"><i class="icon-map-marker"></i>Yankee Stadium</span>
                            </li>
                        </ul>
                        <p class="text-center">
                            <a href="#" class="more button secondary">See more event</a>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h2 class="section-title"><i class="icon-book"></i> Courses</h2>
                        <ul class="posts">
                            <li class="post">
                                <h3 class="entry-title"><a href="#">Nostrud exercitation ullamco</a></h3>
                                <span class="date"><i class="icon-calendar"></i> 6 APR 2014</span>
                                <span class="time"><i class="icon-clock"></i>1:00pm-2:00pm</span>
                                <span class="price"><i class="icon-coins"></i>$55.00</span>
                            </li>
                            <li class="post">
                                <h3 class="entry-title"><a href="#">Nostrud exercitation ullamco</a></h3>
                                <span class="date"><i class="icon-calendar"></i> 6 APR 2014</span>
                                <span class="time"><i class="icon-clock"></i>1:00pm-2:00pm</span>
                                <span class="price"><i class="icon-coins"></i>$55.00</span>
                            </li>
                            <li class="post">
                                <h3 class="entry-title"><a href="#">Nostrud exercitation ullamco</a></h3>
                                <span class="date"><i class="icon-calendar"></i> 6 APR 2014</span>
                                <span class="time"><i class="icon-clock"></i>1:00pm-2:00pm</span>
                                <span class="price"><i class="icon-coins"></i>$55.00</span>
                            </li>
                        </ul>
                        <p class="text-center">
                            <a href="#" class="more button secondary">See more courses</a>
                        </p>
                    </div>
                </div> <!-- .row -->
            </div>
        </div> <!-- .fullwidth-block -->

        <div class="fullwidth-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="boxed-section request-form">
                            <h2 class="section-title text-center">Request information</h2>
                            <form action="#">
                                <div class="field">
                                    <label for="email">Email Address:</label>
                                    <div class="control"><input type="text" id="email" placeholder="example@mail.com"></div>
                                </div>
                                <div class="field">
                                    <label for="name">Your name:</label>
                                    <div class="control"><input type="text" id="name" placeholder="John Smith"></div>
                                </div>
                                <div class="field">
                                    <label for="interest">Campus of Interest</label>
                                    <div class="control">
                                        <select name="#" id="interest">
                                            <option value="other">Other</option>
                                            <option value="other">Phisycs</option>
                                            <option value="other">Chemystry</option>
                                            <option value="other">Art</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="program">Program of Interest</label>
                                    <div class="control">
                                        <select name="#" id="program">
                                            <option value="other">Other</option>
                                            <option value="other">Phisycs</option>
                                            <option value="other">Chemystry</option>
                                            <option value="other">Art</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field no-label">
                                    <div class="control">
                                        <input type="submit" class="button" value="Submit request">
                                    </div>
                                </div>
                            </form>
                        </div> <!-- .boxed-section .request-form -->
                    </div>
                    <div class="col-md-6">
                        <div class="boxed-section best-students">
                            <h2 class="section-title  text-center">Our best students</h2>
                            <ul class="best-students-grid">
                                <li class="student"><a href="#"><img src="{{ asset('dummy/student-md-1.jpg') }}" alt="student 1"></a></li>
                                <li class="student"><a href="#"><img src="{{ asset('dummy/student-md-2.jpg') }}" alt="student 2"></a></li>
                                <li class="student"><a href="#"><img src="{{ asset('dummy/student-md-3.jpg') }}" alt="student 3"></a></li>
                                <li class="student"><a href="#"><img src="{{ asset('dummy/student-md-4.jpg') }}" alt="student 4"></a></li>
                                <li class="student"><a href="#"><img src="{{ asset('dummy/student-md-5.jpg') }}" alt="student 5"></a></li>
                                <li class="student"><a href="#" class="more-student">See more</a></li>
                            </ul>
                        </div> <!-- .boxed-section .best-students -->
                    </div>
                </div>  <!-- .row -->
            </div> <!-- .container -->
        </div> <!-- .fullwidth-block -->
    </main>
</x-layout>