<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
		
		<title>{{ \App\Helpers\SettingsHelper::getSchoolName() }}</title>
		<!-- Loading third party fonts -->
		<link href="http://fonts.googleapis.com/css?family=Arvo:400,700|" rel="stylesheet" type="text/css">
		<link href="{{ asset('fonts/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
		<!-- Loading main css file -->
		<link rel="stylesheet" href="{{ asset('style.css') }}">
		
		<!--[if lt IE 9]>
		<script src="js/ie-support/html5.js"></script>
		<script src="js/ie-support/respond.js"></script>
		<![endif]-->

	</head>


	<body>
		
		<div id="site-content">
			<header class="site-header">
				<div class="primary-header">
					<div class="container">
						<a href="/" id="branding">
							@if(\App\Helpers\SettingsHelper::hasSchoolLogo())
								<img src="{{ \App\Helpers\SettingsHelper::getSchoolLogoUrl() }}" alt="{{ \App\Helpers\SettingsHelper::getSchoolName() }} Logo">
							@else
								<img src="{{ asset('images/logo.png') }}" alt="{{ \App\Helpers\SettingsHelper::getSchoolName() }} Logo">
							@endif
							<h1 class="site-title">{{ \App\Helpers\SettingsHelper::getSchoolName() }}</h1>
						</a> <!-- #branding -->
						
						<div class="main-navigation">
							<button type="button" class="menu-toggle"><i class="fa fa-bars"></i></button>
							<ul class="menu">
							<li class="menu-item current-menu-item"><a href="{{ route('home') }}">Home</a></li>
							<li class="menu-item"><a href="{{ route('announcements.public') }}">Announcements</a></li>
							<li class="menu-item"><a href="{{ route('events') }}">Events</a></li>
							<li class="menu-item"><a href="{{ route('gallery') }}">Gallery</a></li>
							<li class="menu-item"><a href="{{ route('contact.index') }}">Contact</a></li>
							</ul> <!-- .menu -->
						</div> <!-- .main-navigation -->

						<div class="mobile-navigation"></div>
					</div> <!-- .container -->
				</div> <!-- .primary-header -->

                <div class="home-slider">
                    <div class="container">
                        <div class="slider">
                            <ul class="slides">
                                <li>
                                    <div class="slide-caption">
                                        <h2 class="slide-title">I Love to learn! <br> Welcome back school!</h2>
                                        <p>Perspiciatis unde omnis iste natus error sit voluptatem accusantium totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                                        <a href="#" class="button primary large">Learn more</a>
                                    </div>
                                    <img src="{{ asset('dummy/kid.png') }}" alt="">
                                </li>
                                <li>
                                    <div class="slide-caption">
                                        <h2 class="slide-title">I Love to Read! <br> Welcome back school!</h2>
                                        <p>Perspiciatis unde omnis iste natus error sit voluptatem accusantium totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                                        <a href="#" class="button primary large">Learn more</a>
                                    </div>
                                    <img src="{{ asset('dummy/kid.png') }}" alt="">
                                </li>
                                <li>
                                    <div class="slide-caption">
                                        <h2 class="slide-title">I Love to Play! <br> Welcome back school!</h2>
                                        <p>Perspiciatis unde omnis iste natus error sit voluptatem accusantium totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                                        <a href="#" class="button primary large">Learn more</a>
                                    </div>
                                    <img src="{{ asset('dummy/kid.png') }}" alt="">
                                </li>
                            </ul> <!-- .slides -->
                        </div> <!-- .slider -->
                    </div> <!-- .container -->
                </div> <!-- .home-slider -->
            </header>
        </div>

        {{ $slot }}

        <footer class="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="widget">
                            <h3 class="widget-title">Contact us</h3>
                            <address>{{ \App\Helpers\SettingsHelper::getSchoolName() }}<br>{{ \App\Helpers\SettingsHelper::getSchoolAddress() }}</address>

                            @if(\App\Helpers\SettingsHelper::getSchoolEmail())
                                <a href="mailto:{{ \App\Helpers\SettingsHelper::getSchoolEmail() }}">{{ \App\Helpers\SettingsHelper::getSchoolEmail() }}</a> <br>
                            @endif
                            @if(\App\Helpers\SettingsHelper::getSchoolPhone())
                                <a href="tel:{{ \App\Helpers\SettingsHelper::getSchoolPhone() }}">{{ \App\Helpers\SettingsHelper::getSchoolPhone() }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget">
                            <h3 class="widget-title">Social media</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                            <div class="social-links circle">
                                <a href="https://web.facebook.com/cghs.lal"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget">
                            <h3 class="widget-title">Featured students</h3>
                            <ul class="student-list">
                                <li><a href="#">
                                        <img src="dummy/student-sm-1.jpg" alt="" class="avatar">
                                        <span class="fn">Sarah Branson</span>
                                        <span class="average">Average: 4,9</span>
                                    </a></li>
                                <li><a href="#">
                                        <img src="dummy/student-sm-2.jpg" alt="" class="avatar">
                                        <span class="fn">Dorothy Smith</span>
                                        <span class="average">Average: 4,9</span>
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget">
                            <h3 class="widget-title">Newsletter</h3>
                            <p>Aspernatur, rerum. Impedit, deleniti suscipit</p>
                            <form action="#" class="subscribe">
                                <input type="email" placeholder="Email Address...">
                                <input type="submit" class="light" value="Subscribe">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="copy">Copyright {{ date('Y') }} {{ \App\Helpers\SettingsHelper::getSchoolName() }}. All rights reserved.</div>
            </div>

        </footer>

        <script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
        <script src="{{ asset('js/plugins.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        
    </body>

</html>