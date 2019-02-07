@extends('app', [
	'active' => '/compendium',
	'js' => [
		'pages/compendium'
	]
])

@section('head')
	<script>
		@if ($searchTerm)
		var searchTerm = '{{ $searchTerm }}';
		@endif
	</script>
@endsection

@section('topContent')
	<div class='minor-media mb-3' hidden>
		<img src='/assets/{{ config('game.slug') }}/cover.jpg' alt='{{ config('game.data.name') }}'>
		<div class='text'>
			<h1>Compendium</h1>
		</div>
	</div>
@endsection

@section('content')
		<div class='row' id='compendium'>
			<div class='col-md-9 order-md-2'>
				<div class='card card--clean'>
					{{-- Results Header --}}
					<header class='card__header card__header--shop-filter'>
						<div class='shop-filter'>
							<h5 class='shop-filter__result'>Showing X of Y <span>Items</span></h5>
							<ul class='shop-filter__params'>
								<li class='shop-filter__control'>
									<select name='sort' class='form-control input-xs'>
										<option value='name:asc'>Name: A-Z</option>
										<option value='name:desc'>Name: Z-A</option>
										<option value='ilvl:asc'>iLv: Low to High</option>
										<option value='ilvl:desc'>iLv: High to Low</option>
									</select>
								</li>
								<li class='shop-filter__control'>
									<select name='perPage' class='form-control input-xs'>
										<option value='12'>Show 12 per page</option>
										<option value='24'>Show 24 per page</option>
										<option value='36'>Show 36 per page</option>
									</select>
								</li>
							</ul>
						</div>
					</header>
					{{-- Results --}}
					<div class='card__content'>
						<div id='pre-results' class='jumbotron'>
							<h1 class='display-4'>What are you looking for?</h1>
							<p class='lead mt-4 mb-0'>Select a <i class='fas fa-bookmark'></i> chapter and start <i class='fas fa-filter'></i> filtering!</p>
						</div>

						<div id='no-results' class='jumbotron' hidden>
							<h1 class='display-4'>No results!</h1>
							<p class='lead mt-4 mb-0'>Tweak those <i class='fas fa-filter'></i> filters!</p>
						</div>

						{{-- Results --}}
						<ul class='products products--grid products--grid-3 products--grid-simple' hiddenx>
							{{-- Product #0 --}}
							<li class='product__item'>

								<div class='product__img'>
									<div class='product__thumb'>
										{{-- <img src='assets/images/esports/samples/product-1.jpg' alt=''> --}}
									</div>
									<div class='product__overlay'>
										<div class='product__btns'>
											<a href='_esports_shop-cart.html' class='btn btn-primary-inverse btn-block btn-icon'><i class='icon-bag'></i> Add to your bag</a>
											<a href='_esports_shop-wishlist.html' class='btn btn-primary btn-block btn-icon'><i class='icon-heart'></i> Add to wishlist</a>
										</div>
									</div>
								</div>

								<div class='product__content card__content'>
									<div class='product__header'>
										<div class='product__header-inner'>
											<h2 class='product__title'><a href='_esports_shop-product.html'>Jaxxy Framed Art Print</a></h2>
											<div class='product__ratings'>
												<i class='fa fa-star'></i>
												<i class='fa fa-star'></i>
												<i class='fa fa-star'></i>
												<i class='fa fa-star'></i>
												<i class='fa fa-star'></i>
											</div>
											<div class='product__price'>
													$48.00
											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>

				{{-- Results Pagination --}}
				<nav class='shop-pagination' aria-label='Shop navigation' hidden>
					<ul class='pagination pagination--circle justify-content-center'>
						<li class='page-item'><a class='page-link' href='#'><i class='fa fa-angle-left'></i></a></li>
						<li class='page-item active'><a class='page-link' href='#'>1</a></li>
						<li class='page-item'><a class='page-link' href='#'><i class='fa fa-angle-right'></i></a></li>
					</ul>
				</nav>
			</div>

			{{-- Sidebar --}}
			<div class='sidebar sidebar--shop col-md-3 order-md-1'>
				{{-- Chapter Select --}}
				<aside class='widget card widget--sidebar widget_filter-chapter'>
					<form action='#' class='filter-chapter-form'>
						<div class='widget__title card__header card__header--has-btn'>
							<h4>
								<i class='fas fa-book mr-1'></i>
								Chapter
							</h4>
						</div>
						<div class='widget__content card__content'>
							<div class='row'>
								<div class='col-md-6'>
									<div class='form-group form-group--xs my-2'>
										<label class='radio radio-inline'>
											<input type='radio' value='items' v-model='chapter'> Items
											<span class='radio-indicator'></span>
										</label>
									</div>
								</div>
								<div class='col-md-6'>
									<div class='form-group form-group--xs my-2'>
										<label class='radio radio-inline'>
											<input type='radio' value='recipes' v-model='chapter'> Recipes
											<span class='radio-indicator'></span>
										</label>
									</div>
								</div>
								<div class='col-md-6'>
									<div class='form-group form-group--xs my-2'>
										<label class='radio radio-inline'>
											<input type='radio' value='equipment' v-model='chapter'> Equipment
											<span class='radio-indicator'></span>
										</label>
									</div>
								</div>
								{{-- <div class='col-md-6'>
									<div class='form-group form-group--xs my-2'>
										<label class='radio radio-inline'>
											<input type='radio' value='quests' v-model='chapter'> Quests
											<span class='radio-indicator'></span>
										</label>
									</div>
								</div> --}}
							</div>
						</div>
					</form>
				</aside>

				{{-- Filters Defined --}}
				@php
					// Filters array used to generate both filtration anchors and filtration widgets
					$filters = [
						'ilvl' => [
							'for'    => 'items","recipes","equipment',
							'filter' => 'ilvl',
							'type'   => 'range',
							'icon'   => 'fa-info',
							'title'  => 'Item Level',
						],
						'rarity' => [
							'for'    => 'items","recipes","equipment',
							'filter' => 'rarity',
							'type'   => 'multiple',
							'icon'   => 'fa-registered',
							'title'  => 'Rarity',
						],
						'rlevel' => [
							'for'    => 'recipes',
							'filter' => 'rlevel',
							'type'   => 'range',
							'icon'   => 'fa-award',
							'title'  => 'Recipe Level',
						],
						'rclass' => [
							'for'    => 'recipes',
							'filter' => 'rclass',
							'type'   => 'multiple',
							'icon'   => 'fa-chess-bishop',
							'title'  => 'Recipe Class',
							'hidden' => true
						],
						'rdifficulty' => [
							'for'    => 'recipes',
							'filter' => 'sublevel',
							'type'   => 'multiple',
							'icon'   => 'fa-star',
							'title'  => 'Recipe Difficulty',
						],
						'elevel' => [
							'for'    => 'equipment',
							'filter' => 'elevel',
							'type'   => 'range',
							'icon'   => 'fa-medal',
							'title'  => 'Equipment Level',
						],
						'eclass' => [
							'for'    => 'equipment',
							'filter' => 'eclass',
							'type'   => 'multiple',
							'icon'   => 'fa-chess-rook',
							'title'  => 'Equipment Class',
						],
						'slot' => [
							'for'    => 'equipment',
							'filter' => 'slot',
							'type'   => 'multiple',
							'icon'   => 'fa-hand-paper',
							'title'  => 'Equipment Slot',
						],
						'sockets' => [
							'for'    => 'equipment',
							'filter' => 'sockets',
							'type'   => 'multiple',
							'icon'   => 'fa-gem',
							'title'  => 'Materia Sockets',
						],
					];
				@endphp

				{{-- Filters List/Anchors --}}
				<aside class="widget card widget--sidebar -filters">
					<div class="widget__title card__header card__header--has-btn">
						<h4>
							<i class='fas fa-filter'></i>
							Filter By&hellip;
						</h4>
					</div>
					<div class="widget__content card__content">
						<ul class="widget__list">
							@foreach ($filters as $filter)
							<li data-filter='{{ $filter['filter'] }}' v-if='["{!! $filter['for'] !!}"].includes(chapter) && ! activeFilters.includes("{{ $filter['filter'] }}")'>
								<a href='#' @click.prevent='activeFilters.push("{{ $filter['filter'] }}")'>
									<i class='fas {{ $filter['icon'] }} mr-1'></i>
									{{ $filter['title'] }}
								</a>
							</li>
							@endforeach
							<li class='clear-filters'>
								<a href='#' @click.prevent='activeFilters = []'>
									<i class='fas fa-broom mr-1'></i>
									Clear Filters
								</a>
							</li>
						</ul>
					</div>
				</aside>

				{{-- Filter Widgets --}}
				@component('game.compendium.widget', $filters['ilvl'])
					<div class='slider-range-wrapper'>
						<div class='slider-range' data-keys='ilvlMin,ilvlMax' data-min='1' data-max='{{ $max['ilvl'] }}'></div>
						<div class='slider-range-label'>
							iLv: <span class='min'></span> - <span class='max'></span>
						</div>
					</div>
				@endcomponent

				@component('game.compendium.widget', $filters['rarity'])
					<div class='row'>
						@foreach (config('game.rarity') as $rarityKey => $rarity)
							<div class='col-md-6'>
								<div class='form-group form-group--xs'>
									<label class='checkbox checkbox-inline'>
										<input type='checkbox' name='rarity[]' id='rarity-{{ $rarityKey }}' value='{{ $rarityKey }}' checked> {{ $rarity }}
										<span class='checkbox-indicator'></span>
									</label>
								</div>
							</div>
						@endforeach
					</div>
				@endcomponent

				@component('game.compendium.widget', $filters['rlevel'])
					<div class='slider-range-wrapper'>
						<div class='slider-range' data-keys='rlvlMin,rlvlMax' data-min='1' data-max='{{ $max['rlvl'] }}'></div>
						<div class='slider-range-label'>
							rLv: <span class='min'></span> - <span class='max'></span>
						</div>
					</div>
				@endcomponent

				@component('game.compendium.widget', $filters['rclass'])
					<ul class='filter-color'>
						@foreach ($jobs['crafting'] as $jobTier => $jobSet)
						@foreach ($jobSet->sortBy('id') as $job)
							<li class='filter-color__item'>
								<label class='checkbox' data-toggle='tooltip' title='{{ $job->name }}' for='rclassId{{ $job->id }}'>
									<input type='checkbox' name='rclass[]' value='{{ $job->id }}' id='rclassId{{ $job->id }}' hidden>
									<img src='/assets/{{ config('game.slug') }}/jobs/crafting-{{ $job->abbreviation }}.png' class='checkbox-indicator' alt='{{ $job->abbreviation }}' width='24' height='24'>
								</label>
							</li>
						@endforeach
						@endforeach
					</ul>
				@endcomponent

				@component('game.compendium.widget', $filters['rdifficulty'])
					<div class='row'>
						@foreach (range(0, config('game.maxDifficulty')) as $sublevel)
							<div class='col-md-6'>
								<div class='form-group form-group--xs'>
									<label class='checkbox checkbox-inline'>
										<input type='checkbox' name='sublevel[]' id='sublevel-{{ $sublevel }}' value='{{ $sublevel }}' checked>
										<span class='checkbox-indicator'></span>
										@if ($sublevel == 0)
											Base Difficulty
										@else
											@foreach (range(1, $sublevel) as $star)
											<span class='sublevel-icon'></span>
											@endforeach
										@endif
									</label>
								</div>
							</div>
						@endforeach
					</div>
				@endcomponent

				@component('game.compendium.widget', $filters['elevel'])
					<div class='slider-range-wrapper'>
						<div class='slider-range' data-keys='elvlMin,elvlMax' data-min='1' data-max='{{ $max['elvl'] }}'></div>
						<div class='slider-range-label'>
							eLv: <span class='min'></span> - <span class='max'></span>
						</div>
					</div>
				@endcomponent

				@component('game.compendium.widget', $filters['eclass'])
					<ul class='filter-color'>
						@foreach ($jobs as $jobType => $jobTiers)
						@foreach ($jobTiers as $jobTier => $jobSet)
						@foreach ($jobSet->sortBy('id') as $job)
							<li class='filter-color__item {{ $jobType }}-job'>
								<label class='checkbox' data-toggle='tooltip' title='{{ $job->name }}' for='eclassId{{ $job->id }}'>
									<input type='checkbox' name='eclass[]' value='{{ $job->id }}' id='eclassId{{ $job->id }}' hidden>
									<img src='/assets/{{ config('game.slug') }}/jobs/{{ $job->abbreviation }}.png' class='checkbox-indicator' alt='{{ $job->abbreviation }}' width='24' height='24'>
								</label>
							</li>
						@endforeach
						@endforeach
						@endforeach
					</ul>
				@endcomponent

				@component('game.compendium.widget', $filters['slot'])
					<ul class='filter-color'>
						@foreach (collect(config('game.equipmentLayout'))->unique() as $name => $key)
							<li class='filter-color__item'>
								<label class='checkbox' data-toggle='tooltip' title='{{ $name }}' for='slotId{{ $key }}'>
									<input type='checkbox' name='slot[]' value='{{ $key }}' id='slotId{{ $key }}' hidden>
									<img src='/assets/{{ config('game.slug') }}/slots/{{ $key }}.png' alt='{{ $name }}' class='checkbox-indicator' width='24' height='24'>
								</label>
							</li>
						@endforeach
					</ul>
				@endcomponent

				@component('game.compendium.widget', $filters['sockets'])
					<div class='row'>
						@foreach (range(0, config('game.maxSockets')) as $sockets)
							<div class='col-md-6'>
								<div class='form-group form-group--xs'>
									<label class='checkbox checkbox-inline'>
										<input type='checkbox' name='sockets[]' id='sockets-{{ $sockets }}' value='{{ $sockets }}' checked>
										<span class='checkbox-indicator'></span>
										@if ($sockets == 0)
											Socketless
										@else
											@foreach (range(1, $sockets) as $gem)
											<span class='fas fa-gem' style='font-size: 13px;'></span>
											@endforeach
										@endif
									</label>
								</div>
							</div>
						@endforeach
					</div>
				@endcomponent
			</div>
		</div>

		<div class='compendium-item -template media' hidden>
			<img src='' alt='' width='48' height='48'>
			<div class='media-body'>
				<h6 class='name'></h6>
				<div class='secondary'>
					<span class='ilvl'></span>
					<span class='recipes'>
						<span class='job'><img src='' alt='' width='24' height='24'><img src='' alt='' width='24' height='24'><span class='multiple'>✚</span></span>
						<span class='level'></span>
					</span>
					<span class='equipment'>
						<span class='job'><img src='' alt='' width='24' height='24'><img src='' alt='' width='24' height='24'><span class='multiple'>✚</span></span>
						<span class='level'></span>
					</span>

					<div class='category'></div>
				</div>
			</div>
			<div class='button'>
				<button type='button' class='btn btn-light add-to-list' data-id='' data-type='item'>
					<img src='/images/icons/swap-bag.svg' class='svg-icon' alt=''>
					<span class='badge badge-light' hidden></span>
				</button>
			</div>
		</div>


@endsection
