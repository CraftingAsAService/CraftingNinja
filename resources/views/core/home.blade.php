@extends('app', [
	'title' => '',
	'description' => '',
	'keywords' => '',
	'robots' => '',
	'js' => [

	]
])

@section('content')

<h1>Home</h1>

<h2>Games!</h2>
<ul>
	@foreach ($allGames as $game)
		<ul>
			<a href='{{ buildGameURL($game->slug) }}'>{{ $game->name }}</a>
		</ul>
	@endforeach
</ul>

<h2>Links</h2>

...

@stop