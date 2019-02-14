<!DOCTYPE html>
<html lang='{{ \App::getLocale() }}'>
	<head>
		<meta charset='UTF-8'>
		<meta name='description' content='Crafting Information and Planning for your Favorite Game'>
		<meta name='author' content='Nick Wright'>
		<meta name='keywords' content='crafting video game planning efficient'>
		<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
		<meta http-equiv='X-UA-Compatible' content='ie=edge'>
		<link href='/images/favicon@2x.png' rel='icon' type='image/png'>
		<meta name='csrf-token' content='{{ csrf_token() }}'>

		<title>
			@if (config('game.data.name'))
			{{ config('game.data.name') }} | Crafting Ninja
			@else
			Crafting Ninja
			@endif
		</title>

		<link rel='stylesheet' href='/css/app.css'>
		@if (config('game.slug'))
		<link rel='stylesheet' href='/css/themes/{{ config('game.slug') }}.css'>
		@endif

		<script>
			var
				cookieDomain = '{{ config('app.base_url') }}',
				@if (config('game.slug'))
				game = {
					slug: '{{ config('game.slug') }}'
				},
				@endif
				assets = [
					@if (isset($js))
					@foreach ($js as $script)
						'/js/{!! $script !!}.js'{{ $loop->last ? '' : ',' }}
					@endforeach
					@endif
				];
		</script>

		<link rel='stylesheet' href='//fonts.googleapis.com/css?family=Inknut+Antiqua|Roboto+Condensed'>
		<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css' integrity='sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ' crossorigin='anonymous'>
	</head>
	<body class='{{ config('game.slug') }}'>
		<header>
			<nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark'>
				<a class='navbar-brand' href='/'>
					<img src='/images/favicon@2x.png' width='27' height='27'>
					@if (config('game.slug'))
						<span class='ml-1'>{{ strtoupper(config('game.slug')) }}</span>
					@endif
				</a>
				<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarCollapse' aria-controls='navbarCollapse' aria-expanded='false' aria-label='Toggle navigation'>
					<span class='navbar-toggler-icon'></span>
				</button>
				<div class='collapse navbar-collapse' id='navbarCollapse'>
					<ul class='navbar-nav mr-auto'>
						@if ( ! config('game'))
						<li class='nav-item{{ request()->path() == '/' ? ' active' : '' }}'>
							<a class='nav-link' href='/#games'>Games</a>
						</li>
						@else
						@php
							$navigation = [
								'Equipment'	 => '/equipment',
								'Crafting'	 => '/crafting',
								'Compendium' => '/compendium',
								'Knapsack'	 => '/knapsack',
							];
						@endphp
						@foreach ($navigation as $section => $url)
						<li class='nav-item{{ isset($active) && $active == $url ? ' active' : '' }}'>
							<a class='nav-link' href='{{ $url }}'>{!! $section !!}</a>
						</li>
						@endforeach
						@endif
					</ul>
					<ul class='navbar-nav'>
						<li class='nav-item dropdown'>
							<a class='nav-link dropdown-toggle' href='#' id='localeDropdown' data-toggle='dropdown' data-boundary='window' aria-haspopup='true' aria-expanded='false'><i class='fas fa-language'></i></a>
							<div class='dropdown-menu dropdown-menu-right' aria-labeledby='localeDropdown'>
								@foreach (config('translatable.locales') as $language => $locale)
								<a class='dropdown-item{{ $locale == app()->getLocale() ? ' active' : '' }}' href='/locale/{{ $locale }}'>{!! $language !!}</a>
								@endforeach
							</div>
						</li>
						<li class='nav-item dropdown'>
							<a class='nav-link dropdown-toggle' href='#' id='localeDropdown' data-toggle='dropdown' data-boundary='window' aria-haspopup='true' aria-expanded='false'>
								@if (auth()->check())
								<img src='{{ auth()->user()->avatar ?? 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(auth()->user()->email))) . '?s=27&d=retro'}}' class='avatar' width='27' height='27'>
								@else
								<i class='far fa-user-circle'></i>
								@endif
							</a>
							<div class='dropdown-menu dropdown-menu-right' aria-labeledby='localeDropdown'>
								@if (auth()->check())
								<a class='dropdown-item' href='/logout'>
									<i class='fas fa-sign-out-alt'></i> Sign out
								</a>
								@else
								{{--
								<a class='dropdown-item' href='/login/patreon'>
									<i class='fab fa-patreon'></i> Sign in
								</a>
								--}}
								<a class='dropdown-item' href='/login/google'>
									<i class='fab fa-google'></i> Sign in
								</a>
								@endif
							</div>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<main role='main'>
			@yield('topContent')

			<div class='container pt-3'>
				@include('flash::message')

				@if ($errors->any())
					<div class='alert alert-danger'>
						<ul class='mb-0'>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				@yield('content')
			</div>
		</main>

		{{--
		<footer class='footer'>
			<div class='container'>

			</div>
		</footer>
		--}}
		<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js' integrity='sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=' crossorigin='anonymous'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' integrity='sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49' crossorigin='anonymous'></script>
		<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js' integrity='sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy' crossorigin='anonymous'></script>
		{{-- <script src='https://cdn.jsdelivr.net/npm/vue{{ config('app.env') == 'production' ? '' : '/dist/vue.js' }}'></script>
		<script src="https://unpkg.com/axios/dist/axios.min.js"></script> --}}
		<script src='/js/core.js'></script>
	</body>
</html>
