@extends('app', [
	'active' => '/sling',
	'css' => [
		// 'pages/sling',
	],
	'js' => [
		'pages/sling',
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
			<h1>Sling</h1>
		</div>
	</div>
@endsection

@section('content')

	<div id='sling'>
		<div class='card card--no-paddings alc' v-if='contents.length'>
			<div class='card__header card__header--has-btn'>
				<h4>Your Sling</h4>
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
										<span class='badge badge-recipe' role='info' v-if='entry.type == "recipe"'><img :src='"/assets/{{ config('game.slug') }}/jobs/crafting-" + entry.job.icon + ".png"' :alt='entry.job.name'></span>
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
				<div class='row align-items-center'>
					<div class='col-sm-3'>
						{{-- <a href='#' class='btn btn-secondary btn-sm btn-block'>Share List</a> --}}
					</div>
					<div class='col-sm-6 text-center'>
						<a href='/craft/sling' class='btn btn-primary btn-lg'>Start Crafting</a>
					</div>
					<div class='col-sm-3'>
						<a href='/scrolls/create' class='btn btn-secondary btn-sm btn-block'>Scribe Scroll</a>
					</div>
				</div>
			</div>
		</div>
		<div v-else>
			<div id='no-results' class='jumbotron'>
				<h1 class='display-4'>Nothing in your Sling!</h1>
				<p class='lead mt-4 mb-0'>Head over to the <a href='/compendium'>Compendium</a> to add stuff.</p>
			</div>
		</div>
	</div>

@endsection
