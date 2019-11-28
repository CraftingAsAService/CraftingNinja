<!DOCTYPE html>
<html lang='{{ app()->getLocale() }}'>
<head>

	{{--
		Basic Page Needs
	--}}

	<title>
		@if (config('game.data.name'))
		{{ config('game.data.name') }} | Crafting Ninja
		@else
		Crafting Ninja
		@endif
	</title>
	<meta charset='utf-8'>
	<meta http-equiv='X-UA-Compatible' content='IE=edge'>
	<meta name="csrf-token" content='{{ csrf_token() }}'>
	<meta name='description' content='Crafting Information and Planning for your Favorite Game'>
	<meta name='author' content='Tickthokk'>
	<meta name='keywords' content='crafting video game planning efficient'>
	<link rel='author' href='humans.txt'>

	{{--
		Favicons
	--}}

	<link rel='apple-touch-icon' sizes='180x180' href='/apple-touch-icon.png'>
	<link rel='icon' type='image/png' sizes='32x32' href='/favicon-32x32.png'>
	<link rel='icon' type='image/png' sizes='16x16' href='/favicon-16x16.png'>
	<link rel='manifest' href='/site.webmanifest'>
	<link rel='mask-icon' href='/safari-pinned-tab.svg' color='#6441a4'>
	<meta name='msapplication-TileColor' content='#603cba'>
	<meta name='theme-color' content='#f7f7f7'>

	{{--
		Mobile Specific Metas
	--}}

	<meta name='viewport' content='width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0'>

	{{--
		Google Web Fonts
	--}}

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700|Roboto+Condensed:400,400i,700,700i' rel='stylesheet'>

	{{--
		CSS
	--}}

	{{-- Vendor CSS --}}
	<link href='/alchemists/vendor/bootstrap/css/bootstrap.css' rel='stylesheet'>
	{{-- <link href='/alchemists/fonts/font-awesome/css/font-awesome.min.css' rel='stylesheet'> --}}
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<link href='/alchemists/fonts/simple-line-icons/css/simple-line-icons.css' rel='stylesheet'>
	<link href='/alchemists/vendor/magnific-popup/dist/magnific-popup.css' rel='stylesheet'>
	<link href='/alchemists/vendor/slick/slick.css' rel='stylesheet'>

	{{-- Template CSS --}}
	{{-- <link href='/alchemists/css/style-esports.css' rel='stylesheet'> --}}
	<link href='/css/alchemists/theme.css' rel='stylesheet'>

	{{-- Custom CSS --}}
	<link href='/css/app.css' rel='stylesheet'>

	@if (config('game.slug'))
	<link rel='stylesheet' href='/css/themes/{{ config('game.slug') }}.css'>
	@endif

	@isset($css)
	@foreach($css as $file)
	<link href='/css/{!! $file !!}.css' rel='stylesheet'>
	@endforeach
	@endisset

	<script>
		var
			@if (config('game.slug'))
			game = {
				slug: '{{ config('game.slug') }}'
			},
			@endif
			cookieDomain = '{{ config('app.base_url') }}';
			{{--assets = [
				@if (isset($js))
				@foreach ($js as $script)
					'/js/{!! $script !!}.js'{{ $loop->last ? '' : ',' }}
				@endforeach
				@endif
			];--}}
	</script>

	@yield('head')

</head>
<body class='{{ config('game.slug') }}'>

	<div class='site-wrapper clearfix' id='app'>
		<div class='site-overlay'></div>

		@include('wrapper/header-mobile')

		@include('wrapper/header-desktop')

		@yield('heading')

		@include('wrapper/content')

		@include('wrapper/footer')

	</div>

	{{--
		Javascript Files
	--}}

	@yield('scripts')

	{{-- Core JS --}}
	<script src='/alchemists/vendor/jquery/jquery.min.js'></script>
	<script src='/alchemists/vendor/jquery/jquery-migrate.min.js'></script>
	<script src='/alchemists/vendor/bootstrap/js/bootstrap.bundle.js'></script>
	{{-- <script src='/alchemists/js/core.js'></script> --}}

	{{-- Vendor JS --}}
	{{-- <script src='/alchemists/vendor/twitter/jquery.twitter.js'></script> --}}
	<script src='/alchemists/vendor/jquery-duotone/jquery.duotone.min.js'></script>
	{{-- <script src='/alchemists/vendor/marquee/jquery.marquee.min.js'></script> --}}

	{{-- Vue JS --}}
	<script src='{{ mix('/js/manifest.js') }}'></script>
	<script src='{{ mix('/js/vendor.js') }}'></script>
	<script src='https://unpkg.com/underscore@1.8.3'></script>
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script src='{{ mix('/js/app.js') }}'></script>

	{{-- Template JS --}}
	<script src='/alchemists/js/core.js'></script>
	<script src='/alchemists/js/init.js'></script>

	{{-- Duotone Filters --}}
	@include('wrapper/duotone')

	@isset($js)
	@foreach($js as $file)
	<script src='{{ mix('/js/' . $file . '.js') }}'></script>
	@endforeach
	@endisset

</body>
</html>
