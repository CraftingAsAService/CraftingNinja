<!doctype html>
<html class="no-js" lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<title>{{ $title or 'Crafting As A Service' }}</title>
		@if (isset($description))
		<meta name="description" content="{{ $description }}">
		@endif
		@if (isset($keywords))
		<meta name="keywords" content="{{ $keywords }}">
		@endif
		@if (isset($robots))
		<meta name="robots" content="{{ $robots }}">
		@endif

		<meta name="viewport" content="width=device-width, initial-scale=1">

		{{-- <link rel="stylesheet" href="/css/normalize.css"> --}}

		@if (isset($css))
		@foreach ($css as $file)
		<link href="{{ $file }}" rel="stylesheet">
		@endforeach
		@endif

		<script>
			var scripts = [];
			@if (isset($js))
			scripts.push(
				@foreach ($js as $file)
				'{{ $file }}'{{ $loop->last ? '' : ',' }}
				@endforeach
			);
			@endif
		</script>
	</head>
	<body>
		@include('flash::message')

		@yield('content')

		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
		<script src="/js/core.js"></script>

		<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
		<script>
			(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
			e.src='https://www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
			ga('create','UA-XXXXX-X','auto');ga('send','pageview');
		</script>
	</body>
</html>
