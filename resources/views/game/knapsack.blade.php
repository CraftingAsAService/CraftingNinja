@extends('app', [
	'active' => '/knapsack',
	'css' => [
		// 'pages/knapsack',
	],
	'js' => [
		'pages/knapsack',
	]
])

@section('head')
	<script>
		var ninjaCartContents = @json($ninjaCart);
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

	<div id='knapsack'>
		@if ($ninjaCart->isEmpty())
			<div id='no-results' class='jumbotron'>
				<h1 class='display-4'>Nothing in your Knapsack!</h1>
				<p class='lead mt-4 mb-0'>Head over to the <a href='/compendium'>Compendium</a> to add stuff.</p>
			</div>
		@else
		<div class='card card--no-paddings alc'>
			<div class='card__header card__header--has-btn'>
				<h4>Your Knapsack</h4>
				<a class='btn btn-default btn-xs card-header__button' href='#'>Clear</a>
			</div>
			<div class='card__content pt-3'>
				<div class='alc-inventory'>
					<div class='al-inventory__side'>
						<div class='card__content-inner'>
							<ul class='alc-inventory__list list-unstyled'>
								<li class='alc-inventory__item' v-for='entry in contents'>
									<figure class='alc-inventory__item-thumb'>
										<img :src='"/assets/{{ config('game.slug') }}/item/" + entry.icon + ".png"' alt=''>
									</figure>
									<div class='alc-inventory__item-badges'>
										<span class='badge badge-primary' v-if='entry.quantity > 1' v-html='entry.quantity'></span>
										<span class='badge badge-default badge-close' @click='removeFromCart(entry)'><i class='fa fa-times -desize'></i></span>
									</div>
									<div class='alc-product-wishlist__body text-center mt-1'>
										<h5 :class='"alc-product-wishlist__title rarity-" + entry.rarity' v-html='entry.name'></h5>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class='card__content-inner'>
				<div class='row'>
					<div class='col-sm-3 col-lg-2'>
						{{-- <a href='#' class='btn btn-secondary btn-sm btn-block'>Share List</a> --}}
					</div>
					<div class='col-sm-3 col-lg-2'>
						{{-- <a href='#' class='btn btn-secondary btn-sm btn-block'>Publish List</a> --}}
					</div>
					<div class='col-sm-3 offset-sm-3 col-lg-2 offset-lg-6'>
						<a href='/craft/sack' class='btn btn-primary btn-sm btn-block'>Start Crafting</a>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>

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
