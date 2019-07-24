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
			filterStart      = '{{ $filterStart }}',
			booksFilters     = @json(array_values(config('crafting.filters.books'))),
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
			<div class='post-filter post-filter--boxed mb-3'>
				<form action='#' class='post-filter__form'>
					<div class='post-filter__select'>
						<label class='post-filter__label'>
							<i class='fas fa-bookmark mr-1'></i>
							Chapter
						</label>
						<select class='cs-select cs-skin-border' data-compendium-var='chapter'>
							<option value='books'>Books</option>
							<option value='recipe'>Recipes</option>
							{{--
							<option value='equipment'>Equipment</option>
							<option value='item'>All Items</option>
							<option value='quest'>Quests</option>
							<option value='mob'>Enemies</option>
							--}}
						</select>
					</div>
					<div class='post-filter__select -search'>
						<label class='post-filter__label'>
							<i class='fas fa-search mr-1'></i>
							Search
						</label>
						<input type='text' class='form-control' v-model='filters.name' v-on:input='nameUpdated'>
					</div>
					{{--
					<ninja-dropdown v-if='chapter == "items"' title='Filter By' icon='fas fa-filter' placeholder='Add Filter' option='filter' :options='ninjaFilters.item' @clicked='ninjaDropdownUpdated'></ninja-dropdown>

					<ninja-dropdown v-if='chapter == "recipes"' title='Filter By' icon='fas fa-filter' placeholder='Add Filter' option='filter' :options='ninjaFilters.recipe' @clicked='ninjaDropdownUpdated'></ninja-dropdown>

					<ninja-dropdown v-if='chapter == "equipment"' title='Filter By' icon='fas fa-filter' placeholder='Add Filter' option='filter' :options='ninjaFilters.equipment' @clicked='ninjaDropdownUpdated'></ninja-dropdown>
					--}}
					<ninja-dropdown title='Sorting' icon='fas fa-sort' placeholder='' option='sorting' :options='ninjaFilters.sorting' @clicked='ninjaDropdownUpdated'></ninja-dropdown>

					<ninja-dropdown title='Per Page' icon='fas fa-sticky-note' placeholder='' option='perPage' :options='ninjaFilters.perPage' @clicked='ninjaDropdownUpdated'></ninja-dropdown>

					{{-- <div class='post-filter__submit'>
						<button type='button' class='btn btn-primary btn-block' @click='applyFilters()'>
							<i class='fas fa-check-square mr-1'></i>
							Apply Filters
						</button>
					</div> --}}
				</form>
			</div>

			<div class='row'>
				<div class='col-md-9 order-md-2'>
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
							<div v-if='chapter == "books"'>
								@include('game.compendium.results.books')
							</div>
							<div v-else-if='chapter == "item"'>
								@include('game.compendium.results.items')
							</div>
						</div>
					</div>

					{{-- Results Pagination --}}
					@include('game.compendium.results._pagination')
				</div>

				{{-- Sidebar --}}
				<div class='sidebar sidebar--shop col-md-3 order-md-1'>

					{{-- Filter Widgets --}}

					@include('game.compendium.filters.blvl')
					@include('game.compendium.filters.bclass')
					@include('game.compendium.filters.badditional')
					@include('game.compendium.filters.ilvl')
					@include('game.compendium.filters.rclass')
					@include('game.compendium.filters.rlvl')
					@include('game.compendium.filters.rdifficulty')
					@include('game.compendium.filters.elvl')
					@include('game.compendium.filters.eclass')
					@include('game.compendium.filters.slot')
					@include('game.compendium.filters.sockets')
					@include('game.compendium.filters.rarity')

				</div>
			</div>
		</div>

@endsection
