<x-layout>    
    <main class="main-content">
        <div class="fullwidth-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="section-title"><i class="icon-newspaper"></i> Latest News</h2>
                        <ul class="posts">
                            <li class="post">
                                <h3 class="entry-title"><a href="#">Nostrud exercitation ullamco</a></h3>
                                <span class="date"><i class="icon-calendar"></i> 6 APR 2014</span>
                                <span class="author"><i class="icon-user"></i>Marco Baletti</span>
                            </li>
                            <li class="post">
                                <h3 class="entry-title"><a href="#">Nostrud exercitation ullamco</a></h3>
                                <span class="date"><i class="icon-calendar"></i> 6 APR 2014</span>
                                <span class="author"><i class="icon-user"></i>Marco Baletti</span>
                            </li>
                            <li class="post">
                                <h3 class="entry-title"><a href="#">Nostrud exercitation ullamco</a></h3>
                                <span class="date"><i class="icon-calendar"></i> 6 APR 2014</span>
                                <span class="author"><i class="icon-user"></i>Marco Baletti</span>
                            </li>
                        </ul>
                        <p class="text-center">
                            <a href="#" class="more button secondary">See older News</a>
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