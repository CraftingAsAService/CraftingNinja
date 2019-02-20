@extends('app', [
	'active' => '/compendium',
	'js' => [
		'components/ninja-dropdown',
		'pages/compendium'
	]
])

{{-- Filters Defined --}}
@php
	// Filters array used to generate both filtration anchors and filtration widgets
	$filters = [
		'ilvl' => [
			'key'	 => 'ilvl',
			'for'    => 'items","recipes","equipment',
			'type'   => 'range',
			'icon'   => 'fa-info',
			'title'  => 'Item Level',
		],
		'rarity' => [
			'key'	 => 'rarity',
			'for'    => 'items","recipes","equipment',
			'type'   => 'multiple',
			'icon'   => 'fa-registered',
			'title'  => 'Rarity',
		],
		'rlevel' => [
			'key'	 => 'rlevel',
			'for'    => 'recipes',
			'type'   => 'range',
			'icon'   => 'fa-award',
			'title'  => 'Recipe Level',
		],
		'rclass' => [
			'key'	 => 'rclass',
			'for'    => 'recipes',
			'type'   => 'multiple',
			'icon'   => 'fa-chess-bishop',
			'title'  => 'Recipe Class',
			'hidden' => true
		],
		'rdifficulty' => [
			'key'	 => 'sublevel',
			'for'    => 'recipes',
			'type'   => 'multiple',
			'icon'   => 'fa-star',
			'title'  => 'Recipe Difficulty',
		],
		'elevel' => [
			'key'	 => 'elevel',
			'for'    => 'equipment',
			'type'   => 'range',
			'icon'   => 'fa-medal',
			'title'  => 'Equipment Level',
		],
		'eclass' => [
			'key'	 => 'eclass',
			'for'    => 'equipment',
			'type'   => 'multiple',
			'icon'   => 'fa-chess-rook',
			'title'  => 'Equipment Class',
		],
		'slot' => [
			'key'	 => 'slot',
			'for'    => 'equipment',
			'type'   => 'multiple',
			'icon'   => 'fa-hand-paper',
			'title'  => 'Equipment Slot',
		],
		'sockets' => [
			'key'	 => 'sockets',
			'for'    => 'equipment',
			'type'   => 'multiple',
			'icon'   => 'fa-gem',
			'title'  => 'Materia Sockets',
		],
	];
	$itemFilters = [
		'ilvl' => $filters['ilvl'],
		'rarity' => $filters['rarity'],
	];
	$recipeFilters = [
		'ilvl' => $filters['ilvl'],
		'rarity' => $filters['rarity'],
		'rlevel' => $filters['rlevel'],
		'rclass' => $filters['rclass'],
		'rdifficulty' => $filters['rdifficulty'],
	];
	$equipmentFilters = [
		'ilvl' => $filters['ilvl'],
		'rarity' => $filters['rarity'],
		'elevel' => $filters['elevel'],
		'eclass' => $filters['eclass'],
		'slot' => $filters['slot'],
		'sockets' => $filters['sockets'],
	];
@endphp

@section('head')
	<script>
		@if ($searchTerm)
		var searchTerm = '{{ $searchTerm }}';
		@endif
		var itemFilters = @json(array_values($itemFilters)),
			recipeFilters = @json(array_values($recipeFilters)),
			equipmentFilters = @json(array_values($equipmentFilters));
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
		<div id='compendium'>
			<div class='post-filter post-filter--boxed mb-3'>
				<form action='#' class='post-filter__form'>
					<div class='post-filter__select'>
						<label class='post-filter__label'>
							<i class='fas fa-bookmark mr-1'></i>
							Chapter
						</label>
						<select class='cs-select cs-skin-border' data-compendium-var='chapter'>
							<option value='items'>Items</option>
							<option value='recipes'>Recipes</option>
							<option value='equipment'>Equipment</option>
							<option value='quests'>Quests</option>
						</select>
					</div>

					<ninja-dropdown v-if='chapter == "items"' title='Filter By' icon='fas fa-filter' placeholder='Add Filter' option='filter' :options='itemFilters' @clicked='onNinjaDropdownClick'></ninja-dropdown>

					<ninja-dropdown v-if='chapter == "recipes"' title='Filter By' icon='fas fa-filter' placeholder='Add Filter' option='filter' :options='recipeFilters' @clicked='onNinjaDropdownClick'></ninja-dropdown>

					<ninja-dropdown v-if='chapter == "equipment"' title='Filter By' icon='fas fa-filter' placeholder='Add Filter' option='filter' :options='equipmentFilters' @clicked='onNinjaDropdownClick'></ninja-dropdown>

					<div class='post-filter__select'>
						<label class='post-filter__label'>
							<i class='fas fa-sort mr-1'></i>
							Sorting
						</label>
						<select class='cs-select cs-skin-border' data-compendium-var='sorting'>
							<option value='name:asc'>Name: A-Z</option>
							<option value='name:desc'>Name: Z-A</option>
							<option value='ilvl:asc'>iLv: Low to High</option>
							<option value='ilvl:desc'>iLv: High to Low</option>
						</select>
					</div>
					<div class='post-filter__select'>
						<label class='post-filter__label'>
							<i class='fas fa-grip-vertical mr-1'></i>
							Per Page
						</label>
						<select class='cs-select cs-skin-border' data-compendium-var='perPage'>
							<option value='15'>Show 15 per page</option>
							<option value='30'>Show 30 per page</option>
							<option value='45'>Show 45 per page</option>
						</select>
					</div>
					<div class='post-filter__submit'>
						<button type='button' class='btn btn-primary btn-block' @click='search()'>
							<i class='fas fa-check-square mr-1'></i>
							Filter
						</button>
					</div>
				</form>
			</div>

			<div class='row'>
				<div :class='activeFilters.length > 0 ? "col-md-9 order-md-2" : "col-md-12"'>
					<div class='card card--clean'>
						{{-- Results --}}
						<div class='card__content'>
							<div id='pre-results' class='jumbotron' v-if='results.data.length == 0 && firstLoad'>
								<h1 class='display-4'>What are you looking for?</h1>
								<p class='lead mt-4 mb-0'>Select a <i class='fas fa-bookmark'></i> chapter and start <i class='fas fa-filter'></i> filtering!</p>
							</div>

							<div id='no-results' class='jumbotron' v-if='results.data.length == 0 && ! firstLoad'>
								<h1 class='display-4'>No results!</h1>
								<p class='lead mt-4 mb-0'>Tweak those <i class='fas fa-filter'></i> filters!</p>
							</div>

							{{-- Results --}}
							<ul class='products products--grid products--grid-5 products--grid-simple'>

								<li class='product__item' v-for='(data, index) in results.data'>
									<div class='product__img'>
										<div class='product__thumb'>
											<img v-bind:src='"/assets/{{ config('game.slug') }}/item/" + data.icon + ".png"' v-bind:alt='"Image of " + data.id'>
											<span class='levels badge badge-pill badge-light'>
												<span class='ilvl' v-html='data.ilvl'></span>
												<span class='rlvl' v-if='data.recipes && data.ilvl != data.recipes[0].level' v-html='data.recipes[0].level'></span>
												<span class='elvl' v-if='data.equipment && data.ilvl != data.equipment.level' v-html='data.equipment.level'></span>
											</span>
											<span class='category badge badge-pill badge-secondary' v-if='data.category' v-html='data.category'></span>
										</div>
										<div class='product__overlay'>
											<div class='product__btns'>
												<a href='#' class='btn btn-primary-inverse btn-block btn-icon'><i class='icon-bag'></i> Add to bag</a>
											</div>
										</div>
									</div>
									<div class='jobs'>
										<span class='rjobs'>
											<span class='many-classes' v-if='data.recipes && data.recipes.length > 2' v-tooltip:bottom='data.recipes.length + " Classes can Craft"'>
												<i class='fas fa-user-ninja'></i>
												<i class='fas fa-plus'></i>
											</span>
											<span class='few-classes' v-else>
												<img width='24' height='24' v-for='(recipe, rindex) in data.recipes' v-bind:src='"/assets/{{ config('game.slug') }}/jobs/crafting-" + recipe.job.icon + ".png"'>
											</span>
										</span>
										<span class='ejobs' v-if='data.equipment'>
											<span class='many-classes' v-if='data.equipment && data.equipment.jobs.length > 2' v-tooltip:bottom='data.equipment.jobs.length + " Classes can Equip"'>
												<i class='fas fa-user-ninja'></i>
												<i class='fas fa-plus'></i>
											</span>
											<span class='few-classes' v-else>
												<img width='24' height='24' v-for='(job, jindex) in data.equipment.jobs' v-bind:src='"/assets/{{ config('game.slug') }}/jobs/" + job.icon + ".png"'>
											</span>
										</span>
									</div>
									<div class='product__content card__content'>
										<div class='product__header'>
											<div class='product__header-inner'>
												<h2 v-bind:class='"product__title rarity-" + data.rarity' v-html='data.name'></h2>
											</div>
										</div>
									</div>
									<div class='media' hidden>
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
								</li>
							</ul>
						</div>
					</div>

					{{-- Results Pagination --}}
					<nav class='shop-pagination' aria-label='Shop navigation' v-if='results.data.length > 0'>
						<ul class='pagination pagination--circle justify-content-center'>
							<li class='page-item' v-if='results.links.prev'>
								<a class='page-link' href='#' @click.prevent='previousPage()'>
									<i class='fa fa-angle-left'></i>
								</a>
							</li>
							<li class='page-item active'>
								<a class='page-link' href='#' @click.prevent v-html='results.meta.current_page'></a>
							</li>
							<li class='page-item' v-if='results.links.next'>
								<a class='page-link' href='#' @click.prevent='nextPage()'>
									<i class='fa fa-angle-right'></i>
								</a>
							</li>
						</ul>
					</nav>
				</div>

				{{-- Sidebar --}}
				<div class='sidebar sidebar--shop col-md-3 order-md-1' v-if='activeFilters.length > 0'>


					{{-- Filters List/Anchors --}}
					{{-- <aside class='widget card widget--sidebar -filters'>
						<div class='widget__title card__header card__header--has-btn'>
							<h4>
								<i class='fas fa-filter'></i>
								Filter By&hellip;
							</h4>
						</div>
						<div class='widget__content card__content'>
							<ul class='widget__list'>
								@foreach ($filters as $filter)
								<li data-filter='{{ $filter['key'] }}' v-if='["{!! $filter['for'] !!}"].includes(chapter) && ! activeFilters.includes("{{ $filter['key'] }}")'>
									<a href='#' @click.prevent='activeFilters.push("{{ $filter['key'] }}")'>
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
					</aside> --}}

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
						<div class='checkbox-table'>
							@foreach ($jobs['crafting'] as $jobTier => $jobSet)
							@foreach ($jobSet->sortBy('id') as $job)
								<label class='checkbox checkbox--cell' data-toggle='tooltip' title='{{ $job->name }}' for='rclassId{{ $job->id }}'>
									<input type='checkbox' name='rclass[]' value='{{ $job->id }}' id='rclassId{{ $job->id }}' hidden>
									<span class='checkbox-indicator'><img src='/assets/{{ config('game.slug') }}/jobs/crafting-{{ $job->abbreviation }}.png' alt='{{ $job->abbreviation }}'></span>
								</label>
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
		</div>

@endsection
