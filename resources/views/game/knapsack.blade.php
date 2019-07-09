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
		<div class='card card--no-paddings alc' v-if='contents.length'>
			<div class='card__header card__header--has-btn'>
				<h4>Your Knapsack</h4>
				<button class='btn btn-default btn-xs card-header__button' @click='clearCart()'>Clear</button>
			</div>
			<div class='card__content pt-3'>
				<div class='alc-inventory row'>
					<div :class='"col-sm-" + (active === null ? 12 : 9)'>
						<div class='card__content-inner'>
							<ul class='alc-inventory__list list-unstyled'>
								<li :class='"alc-inventory__item" + (active === entry ? " alc-inventory__item--active" : "")' v-for='(entry, index) in contents' @click='activate(index)'>
									<figure class='alc-inventory__item-thumb'>
										<img :src='"/assets/{{ config('game.slug') }}/item/" + entry.icon + ".png"' alt=''>
									</figure>
									<div class='alc-inventory__item-badges'>
										<span class='badge badge-primary' role='info' v-if='entry.quantity > 1' v-html='entry.quantity'></span>
										<span class='badge badge-default badge-close' @click.stop='removeFromCart(index, "index")'><i class='fa fa-times -desize'></i></span>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div v-if='active !== null' class='col-sm-3' style='border-left: 1px solid var(--color-dark-lighten-2);'>
						<div class='card__content-inner text-center'>
							<h4 :class='"rarity-" + active.rarity' v-html='active.name'></h4>

							<div class='alc-inventory__main-quantity'>
								<input type='number' class='form-control product-quantity-control' min='1' v-model='active.quantity'>
							</div>

							<footer class='alc-inventory__main-footer'>
								<a href='#' class='btn btn-default btn-sm btn-icon' @click='removeFromCart(active.id, active.type)'><i class='fa fa-times'></i> Remove</a>
							</footer>
						</div>
					</div>
				</div>
			</div>
			<div class='card__content p-3 mt-2'>
				<div class='row'>
					<div class='col-sm-3'>
						{{-- <a href='#' class='btn btn-secondary btn-sm btn-block'>Share List</a> --}}
					</div>
					<div class='col-sm-6 text-center'>
						<a href='/craft/knapsack' class='btn btn-primary btn-lg'>Start Crafting</a>
					</div>
					<div class='col-sm-3'>
						{{-- <a href='#' class='btn btn-secondary btn-sm btn-block'>Publish List</a> --}}
					</div>
				</div>
			</div>
		</div>
		<div v-else>
			<div id='no-results' class='jumbotron'>
				<h1 class='display-4'>Nothing in your Knapsack!</h1>
				<p class='lead mt-4 mb-0'>Head over to the <a href='/compendium'>Compendium</a> to add stuff.</p>
			</div>
		</div>
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
