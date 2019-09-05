@extends('app', [
	'active' => '/compendium',
	'css' => [
		'pages/compendium',
	],
	'js' => [
		'pages/compendium',
	]
])

@section('head')
	<script>
		var searchTerm       = '{{ $searchTerm }}',
			chapterStart     = '{{ $chapterStart }}',
			filterStart      = @json($filterStart),
			scrollFilters    = @json(array_values(config('crafting.filters.scroll'))),
			itemFilters      = @json(array_values(config('crafting.filters.item'))),
			recipeFilters    = @json(array_values(config('crafting.filters.recipe'))),
			equipmentFilters = @json(array_values(config('crafting.filters.equipment'))),
			sortingFilters   = @json(array_values(config('crafting.filters.sorting'))),
			perPageFilters   = @json(array_values(config('crafting.filters.perPage')));
	</script>
@endsection

@section('heading')
	@if ($wasReferred)
	@include('game.partials.heading')
	@endif
@endsection

@section('content')
		<div id='compendium'>

			<nav class='content-filter content-filter--highlight-side content-filter--label-left mb-3'>
				<div class='content-filter__inner'>
					<a href='#' class='content-filter__toggle'></a>
					<ul class='content-filter__list'>
						{{-- content-filter__item--active --}}
						<li :class='"content-filter__item" + (chapter == "scroll" ? " content-filter__item--active" : "")'>
							<a href='#' class='content-filter__link'>
								{{-- <small>&nbsp;</small> --}}
								<i class='fas fa-scroll mr-1'></i>
								Scrolls
							</a>
						</li>
						<li :class='"content-filter__item" + (chapter == "recipe" ? " content-filter__item--active" : "")'>
							<a href='#' class='content-filter__link'>
								{{-- <small>&nbsp;</small> --}}
								<i class='fas fa-cogs mr-1'></i>
								Recipes
							</a>
						</li>
						<li :class='"content-filter__item" + (chapter == "item" ? " content-filter__item--active" : "")'>
							<a href='#' class='content-filter__link'>
								{{-- <small>&nbsp;</small> --}}
								<i class='fas fa-box mr-1'></i>
								Items
							</a>
						</li>
						<li :class='"content-filter__item" + (chapter == "equipment" ? " content-filter__item--active" : "")'>
							<a href='#' class='content-filter__link'>
								{{-- <small>Coming Soon</small> --}}
								<i class='fas fa-shield-alt mr-1'></i>
								Equipment
							</a>
						</li>
						<li :class='"content-filter__item" + (chapter == "objective" ? " content-filter__item--active" : "")'>
							<a href='#' class='content-filter__link'>
								{{-- <small>Coming Soon</small> --}}
								<i class='fas fa-bullseye mr-1'></i>
								Objectives
							</a>
						</li>
						<li :class='"content-filter__item" + (chapter == "npc" ? " content-filter__item--active" : "")'>
							<a href='#' class='content-filter__link'>
								{{-- <small>Coming Soon</small> --}}
								<i class='fas fa-bug mr-1'></i>
								NPCs
							</a>
						</li>
					</ul>
				</div>
			</nav>

			<div class='row'>
				<div class='col-md-9 order-md-2'>
					<div class='card card--clean'>
						{{-- Results --}}
						<div class='card__content'>
							<div v-if='results.data.length == 0 && firstLoad'>
								@include('game.compendium.results._pre-results')
							</div>
							<div v-else-if='results.data.length == 0 && ! firstLoad'>
								@include('game.compendium.results._no-results')
							</div>

							{{-- Results --}}
							<div v-if='chapter == "scroll"'>
								@include('game.compendium.results.scrolls')
							</div>
							<div v-else-if='chapter == "item"'>
								@include('game.compendium.results.items')
							</div>
							<div v-else-if='chapter == "recipe"'>
								@include('game.compendium.results.recipes')
							</div>
						</div>
					</div>

					{{-- Results Pagination --}}
					@include('game.compendium.results._pagination')
				</div>

				{{-- Sidebar --}}
				<div class='sidebar sidebar--shop col-md-3 order-md-1'>

					{{-- Filter Widgets --}}
					@include('game.compendium.filters.name')
					@include('game.compendium.filters.slvl')
					@include('game.compendium.filters.sclass', [ 'jobType' => 'crafting'  ])
					@include('game.compendium.filters.sclass', [ 'jobType' => 'gathering' ])
					@include('game.compendium.filters.sclass', [ 'jobType' => 'battle'    ])
					@include('game.compendium.filters.sauthor')
					@include('game.compendium.filters.ilvl')
					@include('game.compendium.filters.rclass')
					@include('game.compendium.filters.rlvl')
					@include('game.compendium.filters.rdifficulty')
					@include('game.compendium.filters.elvl')
					@include('game.compendium.filters.eclass')
					@include('game.compendium.filters.slot')
					@include('game.compendium.filters.sockets')
					@include('game.compendium.filters.rarity')
					@include('game.compendium.filters.refine')

					{{-- <div class='post-filter post-filter--boxed mb-3'>
						<form action='#' class='post-filter__form'>
							<ninja-dropdown title='Sorting' icon='fas fa-sort' placeholder='' option='sorting' :options='ninjaFilters.sorting' @clicked='ninjaDropdownUpdated'></ninja-dropdown>
						</form>
					</div>

					<div class='post-filter post-filter--boxed mb-3'>
						<form action='#' class='post-filter__form'>
							<ninja-dropdown title='Per Page' icon='fas fa-sticky-note' placeholder='' option='perPage' :options='ninjaFilters.perPage' @clicked='ninjaDropdownUpdated'></ninja-dropdown>
						</form>
					</div> --}}

				</div>
			</div>
		</div>

@endsection
