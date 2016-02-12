<?php
	// Config options not always present (maintenance page)
	$lang = config('language');
	if ( ! is_string($lang)) $lang = 'en';
	$lbu = config('language_base_url');
	if ( ! is_string($lbu)) $lbu = $_SERVER['REQUEST_URI'];
?><!DOCTYPE html>
<html lang='en-us'>
	<head>
		<meta http-equiv='X-UA-Compatible' content='IE=Edge'>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="/images/favicon@2x.png" rel="icon" type="image/png">
		<meta name='csrf-token' content='{{ Session::token() }}'>

		<!-- Google Webmaster Tools Verification -->
		<!-- <meta name="google-site-verification" content="31bB29x-UyxdPFc_t--x2BnBY1mGDooQCfGo2XcmlAI"> -->

		<!-- IE11 is stupid -->
		<meta name="msapplication-config" content="none"/>

		<title>Crafting as a Service | Crafting tools for your favorite games</title>
		<meta name='description' content='Crafting as a Service; Tools for your favorite games'>
		<meta name='keywords' content=''>

		<meta charset='utf-8'>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>

		@yield('meta')

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
		{{-- <link href="/css/vendor/bootstrap.min.css" rel="stylesheet"> --}}
		<link href="/css/vendor/tether.min.css" rel="stylesheet">
		<link href="/css/vendor/tether-theme-arrows.min.css" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">

		@yield('vendor-css')

		<!-- CAAS core CSS -->
		<link href="/css/caas.css" rel="stylesheet">

		<!-- New Theme, woot woot! -->
		<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700' rel='stylesheet' type='text/css'>

		@yield('css')
	</head>
	<body>

		<div class="overlay"></div>

		<div class='floating-header'>
			<i class='fa fa-bars open-menu menu-icon hidden-sm-up' data-target='#main-menu'></i>
			<a class="brand" href="/">
				<img src='/images/favicon@2x.png' alt='Logo' width='22' height='22'>
				CaaS
			</a>

			<ul class="nav">
				@if (config('gid'))
				<li class="nav-item">
					<a class="nav-link" href="/games"><i class='fa fa-gamepad'></i><span class='helper-text'>&nbsp;{{ config('game-slug') }}</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/tools"><img src='/images/icons/tools.svg' alt='Tools' class='icon'><span class='helper-text'>&nbsp;Tools</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/compendium"><img src='/images/icons/compendium.svg' alt='Tools' class='icon'><span class='helper-text'>&nbsp;Compendium</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/knapsack"><img src='/images/icons/knapsack.svg' alt='Tools' class='icon'><span class='helper-text'>&nbsp;Knapsack</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link open-menu" href="#" data-target='#account-menu'><i class='fa fa-{{ Auth::check() ? 'user' : 'sign-in' }}'></i></a>
				</li>
				@else
				<li class="nav-item">
					<a class="nav-link" href="/games"><i class='fa fa-gamepad'></i><span class='helper-text'>&nbsp;Select a Game</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link open-menu" href="#" data-target='#account-menu'><i class='fa fa-{{ Auth::check() ? 'user' : 'sign-in' }}'></i><span class='helper-text'>&nbsp;{{ Auth::check() ? 'Manage Account' : 'Login' }}</span></a>
				</li>
				@endif
			</ul>
		</div>

		<div id='primary-wrapper'>

			<main id='main-content'>
				<div id="banner">
					<div class="container">
						@yield('banner')
					</div>
				</div>
				<div id="content">
					@yield('precontent')

					<div class='container'>

						@include('partials.ad', ['class' => 'm-b-1'])

						@include('flash::message')

						@yield('content')

						@include('partials.ad')

					</div>
				</div>

				<div id='footer'>
					<div class='container'>

						<div class="row">
							<div class="col-sm-3">
								<p class="headline">Recent News</p>

								{{-- See /app/Helpers/ViewHelper.php for recent_posts() function --}}

									{{--
								@foreach(recent_posts() as $post)
								<div class="post">
									<div class="title">
										<a href="{{ $post['url'] }}">{{ $post['title'] }}</a>
									</div>
									<div class="date">
										<img src="/img/icons/time.png"><span>{{ $post['created'] }}</span>
									</div>
									<hr>
								</div>
								@endforeach
								--}}

								<p class="view-all"><a href="http://www.reddit.com/r/ffxivcrafting">View All Recent News</a></p>
							</div>
							<div class="col-sm-3">
								<p class="headline">?</p>
							</div>
							<div class="col-sm-3">
								<p class="headline">Donations</p>
								<p>
									I've spent more time building this site than actually playing any of these games.  Buy me a beer!  You'll become an Advanced Crafter and receive some additional benefits on the site.  See your account page for more details!
								</p>
								<a href='/account' class='btn btn-success btn-sm'>Donation Details</a>
							</div>
							<div class="col-sm-3">
								<p class="headline">Other Links</p>

								<div class='row'>
									<div class='col-xs-12 col-md-6'>
										<p><a href="http://www.reddit.com/r/ffxivcrafting">Subreddit</a></p>
										<hr>
									</div>
									<div class='col-xs-12 col-md-6'>
										<p><a href="/report">Report a bug</a></p>
										<hr>
									</div>
									<div class='col-xs-12 col-md-6'>
										<p><a href="mailto:tickthokk@gmail.com">Contact Me</a></p>
										<hr>
									</div>
								</div>
								<p><a href="/credits">Source Credits &amp; Resources</a></p>
							</div>
						</div>
					</div>
				</div>
				<div id="copyright-info">
					<div class="container">
						<div class="row">
							<div class="col-xs-12 col-sm-9">
								{!! date('Y') !!} Crafting as a Service. Individual games are a registered trademark of their respective publisher
							</div>
							<div class="col-xs-12 col-sm-3 text-right">
								<a href="#">Back To Top<span class="fa fa-chevron-up"></span></a>
							</div>
						</div>
					</div>
				</div>
			</main>

			{{-- Begin Left Flyouts --}}
			<nav class='flyout-box' id='main-menu' data-direction='left'>
				<div class='wrapper'>
					<section class='header list-group'>
						<div class='list-group-item'>
							<i class="fa fa-close pull-xs-right close-menu"></i>
							Menu
						</div>
					</section>
					<section class='options list-group'>
						<a href="#" class="list-group-item">
							<i class="fa fa-chevron-right pull-xs-right"></i>
							Cras justo odio
						</a>
						<a href="#" class="list-group-item">
							<i class="fa fa-chevron-right pull-xs-right"></i>
							Dapibus ac facilisis in
						</a>
						<a href="#" class="list-group-item">
							<i class="fa fa-chevron-right pull-xs-right"></i>
							Morbi leo risus
						</a>
						<a href="#" class="list-group-item">
							<i class="fa fa-chevron-right pull-xs-right"></i>
							Porta ac consectetur ac
						</a>
						<a href="#" class="list-group-item">
							<i class="fa fa-chevron-right pull-xs-right"></i>
							Vestibulum at eros
						</a>
					</section>
				</div>
			</nav>
			<nav class='flyout-box' id='game-menu' data-direction='left' data-content='games'></nav>

			{{-- Begin Right Flyouts --}}
			<nav class='flyout-box' id='account-menu' data-direction='right' data-content='account'></nav>
			<nav class='flyout-box' id='language-menu' data-direction='right' data-content='languages'></nav>
		</div>

		@yield('modals')

		<div id='notifications'></div>


		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="/js/vendor/jquery.min.js"><\/script>')</script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
		<script src="/js/vendor/tether.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="/js/vendor/ie10-viewport-bug-workaround.js"></script>
		<script src="/js/vendor/scotchPanels.js"></script>

		<!-- Core CAAS JS -->
		<script src="/js/caas.js"></script>

		@yield('javascript')

									{{--
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-43830923-1', 'ffxivcrafting.com');
			ga('require', 'displayfeatures');
			ga('send', 'pageview');
		</script>
		--}}
	</body>
</html>