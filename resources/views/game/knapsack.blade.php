@extends('app', [
	'active' => '/knapsack',
	'css' => [
		// 'pages/knapsack',
	],
	'js' => [
		// 'pages/knapsack',
	]
])

@section('head')
	<script>
	</script>
@endsection

@section('topContent')
	<div class='minor-media mb-3' hidden>
		<img src='/assets/{{ config('game.slug') }}/cover.jpg' alt='{{ config('game.data.name') }}'>
		<div class='text'>
			<h1>Knapsack</h1>
		</div>
	</div>
@endsection

@section('content')

	@if (empty($ninjaCart))
		<div id='no-results' class='jumbotron'>
			<h1 class='display-4'>Nothing in your Knapsack!</h1>
			<p class='lead mt-4 mb-0'>Head over to the <a href='/compendium'>Compendium</a> to add stuff.</p>
		</div>
	@else
	<div class='card'>
		<div class='card__header card__header--has-btn'>
			<h4>Your Knapsack</h4>
			<a class='btn btn-default btn-xs card-header__button' href='#'>Clear</a>
		</div>
		<div class='card__content pt-0'>
			<ul class='alc-products-wishlist list-unstyled mt-3'>
				@foreach ($ninjaCart as $section => $entries)
					@foreach ($entries as $entry)
				<li class='alc-product-wishlist__item'>
					<figure class='alc-product-wishlist__thumb'>
						<img src='/assets/{{ config('game.slug') }}/item/{{ $entry->icon }}.png' alt=''>
						<a href='#' class='alc-product-wishlist__close btn-circle btn-default btn-xs'>
							<i class='fa fa-times'></i>
						</a>
					</figure>
					<div class='alc-product-wishlist__body'>
						<h5 class='alc-product-wishlist__title rarity-{{ $entry->rarity }}'>{{ $entry->name }}</h5>
						<p>x{{ $entry->quantity }}</p>
					</div>
				</li>
					@endforeach
				@endforeach
			</ul>
		</div>
		<div class='card__content-inner'>
			<div class='row'>
				<div class='col-sm-3 col-lg-2'>
					<a href='#' class='btn btn-secondary btn-sm btn-block'>Share List</a>
				</div>
				<div class='col-sm-3 col-lg-2'>
					<a href='#' class='btn btn-secondary btn-sm btn-block'>Publish List</a>
				</div>
				<div class='col-sm-3 offset-sm-3 col-lg-2 offset-lg-6'>
					<a href='/craft/knapsack' class='btn btn-primary btn-sm btn-block'>Start Crafting</a>
				</div>
			</div>
		</div>
	</div>
	@endif

{{--
@foreach ($listings as $listing)

	@if ($listing->isPublished())
		<h1>{{ $listing->name }}</h1>
		Published
	@else
		Unpublished
	@endif

	@foreach ($listing->items as $item)
		{{ $item->name }} x {{ $item->pivot->quantity }}<br>
	@endforeach

	@foreach ($listing->recipes as $recipe)
		{{ $recipe->product->name }} x {{ $recipe->pivot->quantity }}<br>
	@endforeach

	@foreach ($listing->nodes as $node)
		{{ $node->name }} x {{ $node->pivot->quantity }}<br>
	@endforeach

	@foreach ($listing->objectives as $objective)
		{{ $objective->name }} x {{ $objective->pivot->quantity }}<br>
	@endforeach

@endforeach
--}}

@endsection
