@extends('app')

@section('topContent')
	<div class='jumbotron'>
		<div class='container'>
			<h1 class='display-3'>Crafting as a Service</h1>
			<p class='mt-4 mb-0'>Crafting information and planning for your favorite games</p>
		</div>
	</div>
@endsection

@section('content')
	<div class='container'>
		<h3 class='text-muted mb-3'>Let's get started!</h3>

		<div class='row'>
			@foreach ($games as $game)
			<div class='col-sm-6 col-md-4'>
				<div class='card mb-3'>
					<img class='card-img-top' src='/assets/{{ $game->slug }}/cover.jpg' alt='{{ $game->name }}'>
					<div class='card-body'>
						<h5 class='card-title m-0'>{{ $game->name }}</h5>
						@if ($game->description)
						<p class='card-text mt-3 mb-0'>{{ $game->description }}</p>
						@endif
					</div>
					<div class='card-footer text-right'>
						<a href='https://{{ $game->slug }}.{{ config('app.base_url') }}' class='btn btn-primary'>Start Crafting</a>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
@endsection
