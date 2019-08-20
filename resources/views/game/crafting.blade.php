@extends('app', [
	'active' => '/crafting',
	'css' => [
		// 'pages/sling',
	],
	'js' => [
		// 'pages/sling',
	]
])

@section('topContent')
	<div class='minor-media mb-3' hidden>
		<img src='/assets/{{ config('game.slug') }}/cover.jpg' alt='{{ config('game.data.name') }}'>
		<div class='text'>
			<h1>Crafting</h1>
		</div>
	</div>
@endsection

@section('content')

Blueprints from other users


@endsection
