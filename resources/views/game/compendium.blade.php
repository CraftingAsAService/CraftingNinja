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
		var searchTerm = '{{ $searchTerm }}',
			itemFilters = @json(array_values(config('crafting.filters.item'))),
			recipeFilters = @json(array_values(config('crafting.filters.recipe'))),
			equipmentFilters = @json(array_values(config('crafting.filters.equipment'))),
			sortingFilters = @json(array_values(config('crafting.filters.sorting'))),
			perPageFilters = @json(array_values(config('crafting.filters.perPage')));
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
						<input type='text' class='form-control' v-model='filters.name' v-on:input='debouncedSearch'>
					</div>
					{{--
					<ninja-dropdown v-if='chapter == "items"' title='Filter By' icon='fas fa-filter' placeholder='Add Filter' option='filter' :options='ninjaFilters.item' @clicked='onNinjaDropdownClick'></ninja-dropdown>

					<ninja-dropdown v-if='chapter == "recipes"' title='Filter By' icon='fas fa-filter' placeholder='Add Filter' option='filter' :options='ninjaFilters.recipe' @clicked='onNinjaDropdownClick'></ninja-dropdown>

					<ninja-dropdown v-if='chapter == "equipment"' title='Filter By' icon='fas fa-filter' placeholder='Add Filter' option='filter' :options='ninjaFilters.equipment' @clicked='onNinjaDropdownClick'></ninja-dropdown>
					--}}
					<ninja-dropdown title='Sorting' icon='fas fa-sort' placeholder='' option='sorting' :options='ninjaFilters.sorting' @clicked='onNinjaDropdownClick'></ninja-dropdown>

					<ninja-dropdown title='Per Page' icon='fas fa-sticky-note' placeholder='' option='perPage' :options='ninjaFilters.perPage' @clicked='onNinjaDropdownClick'></ninja-dropdown>

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
							<ul class='products products--grid products--grid-5 products--grid-simple'>

								<li class='product__item' v-for='(data, index) in results.data'>
									<div class='product__img'>
										<div class='product__thumb'>
											<img v-bind:src='"/assets/{{ config('game.slug') }}/item/" + data.icon + ".png"' v-bind:alt='"Image of " + data.id'>
											<span class='levels badge badge-pill badge-light'>
												<span class='ilvl' v-html='data.ilvl'></span>
												<span class='rlvl' v-if='data.recipes && data.ilvl != data.recipes[0].level' v-html='data.recipes[0].level'></span>
												<span class='elvl' v-if='data.equipment && data.ilvl != data.equipment.level' v-html='data.equipment.level'></span>
												<span class='difficulty' v-if='data.recipes && data.recipes[0].sublevel'>
													<span class='sublevel-icon' v-for='n in data.recipes[0].sublevel'></span>
												</span>
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
												<img width='24' height='24' v-for='(recipe, rindex) in data.recipes' v-bind:src='"/assets/{{ config('game.slug') }}/jobs/crafting-" + recipe.job.icon + ".png"' v-tooltip:bottom='recipe.job.icon'>
											</span>
										</span>
										<span class='ejobs' v-if='data.equipment'>
											<span class='many-classes' v-if='data.equipment && data.equipment.jobs.length > 2' v-tooltip:bottom='data.equipment.jobs.length + " Classes can Equip"'>
												<i class='fas fa-user-ninja'></i>
												<i class='fas fa-plus'></i>
											</span>
											<span class='few-classes' v-else>
												<img width='24' height='24' v-for='(job, jindex) in data.equipment.jobs' v-bind:src='"/assets/{{ config('game.slug') }}/jobs/" + job.icon + ".png"' v-tooltip:bottom='job.icon'>
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
				<div class='sidebar sidebar--shop col-md-3 order-md-1'>

					{{-- Filter Widgets --}}
					{{--
					@component('game.compendium.widget', config('crafting.filters.all')['name'])
						<input type='text' class='form-control' v-model='filters.name' v-on:input='debouncedSearch' :placeholder='"Find your " + chapter'>
					@endcomponent --}}

					@component('game.compendium.widget', config('crafting.filters.all')['ilvl'])
						<div class='row'>
							<div class='col'>
								<input type='number' class='form-control min' v-model='filters.ilvlMin' min='1' max='{{ $max['ilvl'] }}' v-on:input='debouncedSearch'>
							</div>
							<div class='col-auto'>
								<i class='fas fa-exchange-alt mt-3'></i>
							</div>
							<div class='col'>
								<input type='number' class='form-control max' v-model='filters.ilvlMax' min='1' max='{{ $max['ilvl'] }}' v-on:input='debouncedSearch'>
							</div>
						</div>
					@endcomponent

					@component('game.compendium.widget', config('crafting.filters.all')['rclass'])
						<div class='checkbox-table'>
							@foreach ($jobs['crafting'] as $jobTier => $jobSet)
							@foreach ($jobSet->sortBy('id') as $job)
								<label class='checkbox checkbox--cell' data-toggle='tooltip' title='{{ $job->name }}' for='rclassId{{ $job->id }}'>
									<input type='checkbox' id='rclassId{{ $job->id }}' v-on:input='toggleFilter("rclass", "{{ $job->id }}")' hidden>
									<span class='checkbox-indicator'><img src='/assets/{{ config('game.slug') }}/jobs/crafting-{{ $job->abbreviation }}.png' alt='{{ $job->abbreviation }}'></span>
								</label>
							@endforeach
							@endforeach
						</ul>
					@endcomponent

					@component('game.compendium.widget', config('crafting.filters.all')['rlvl'])
						<div class='row'>
							<div class='col'>
								<input type='number' class='form-control min' v-model='filters.rlvlMin' min='1' max='{{ $max['rlvl'] }}' v-on:input='debouncedSearch'>
							</div>
							<div class='col-auto'>
								<i class='fas fa-exchange-alt mt-3'></i>
							</div>
							<div class='col'>
								<input type='number' class='form-control max' v-model='filters.rlvlMax' min='1' max='{{ $max['rlvl'] }}' v-on:input='debouncedSearch'>
							</div>
						</div>
					@endcomponent

					@component('game.compendium.widget', config('crafting.filters.all')['rdifficulty'])
						<div class='row'>
							@foreach (range(0, config('game.maxDifficulty')) as $sublevel)
								<div class='col-md-{{ $sublevel == 0 ? 12 : 6 }}'>
									<div class='form-group form-group--xs mb-2'>
										<label class='checkbox checkbox-inline'>
											<input type='checkbox' id='sublevel-{{ $sublevel }}' v-on:input='toggleFilter("sublevel", "{{ $sublevel }}")'>
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

					@component('game.compendium.widget', config('crafting.filters.all')['elvl'])
						<div class='row'>
							<div class='col'>
								<input type='number' class='form-control min' v-model='filters.elvlMin' min='1' max='{{ $max['elvl'] }}' v-on:input='debouncedSearch'>
							</div>
							<div class='col-auto'>
								<i class='fas fa-exchange-alt mt-3'></i>
							</div>
							<div class='col'>
								<input type='number' class='form-control max' v-model='filters.elvlMax' min='1' max='{{ $max['elvl'] }}' v-on:input='debouncedSearch'>
							</div>
						</div>
					@endcomponent

					@component('game.compendium.widget', config('crafting.filters.all')['eclass'])
						<ul class='filter-color'>
							@foreach ($jobs as $jobType => $jobTiers)
							@foreach ($jobTiers as $jobTier => $jobSet)
							@foreach ($jobSet->sortBy('id') as $job)
								<li class='filter-color__item {{ $jobType }}-job'>
									<label class='checkbox' data-toggle='tooltip' title='{{ $job->name }}' for='eclassId{{ $job->id }}'>
										<input type='checkbox' id='eclassId{{ $job->id }}' v-on:input='toggleFilter("eclass", "{{ $job->id }}")' hidden>
										<img src='/assets/{{ config('game.slug') }}/jobs/{{ $job->abbreviation }}.png' class='checkbox-indicator' alt='{{ $job->abbreviation }}' width='24' height='24'>
									</label>
								</li>
							@endforeach
							@endforeach
							@endforeach
						</ul>
					@endcomponent

					@component('game.compendium.widget', config('crafting.filters.all')['slot'])
						<ul class='filter-color'>
							@foreach (collect(config('game.equipmentLayout'))->unique() as $name => $key)
								<li class='filter-color__item'>
									<label class='checkbox' data-toggle='tooltip' title='{{ $name }}' for='slotId{{ $key }}'>
										<input type='checkbox' id='slotId{{ $key }}' v-on:input='toggleFilter("slot", "{{ $key }}")' hidden>
										<img src='/assets/{{ config('game.slug') }}/slots/{{ $key }}.png' alt='{{ $name }}' class='checkbox-indicator' width='24' height='24'>
									</label>
								</li>
							@endforeach
						</ul>
					@endcomponent

					@component('game.compendium.widget', config('crafting.filters.all')['sockets'])
						<div class='row'>
							@foreach (range(0, config('game.maxSockets')) as $sockets)
								<div class='col-md-6'>
									<div class='form-group form-group--xs mb-2'>
										<label class='checkbox checkbox-inline'>
											<input type='checkbox' id='sockets-{{ $sockets }}' v-on:input='toggleFilter("sockets", "{{ $sockets }}")' checked>
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

					@component('game.compendium.widget', config('crafting.filters.all')['rarity'])
						<div class='row'>
							@foreach (config('game.rarity') as $rarityKey => $rarity)
								<div class='col-md-{{ $loop->first ? 12 : 6 }}'>
									<div class='form-group form-group--xs mb-2'>
										<label class='checkbox checkbox-inline' style='color: var(--rarity{{ $rarityKey }});'>
											<input type='checkbox' id='rarity-{{ $rarityKey }}' v-on:input='toggleFilter("rarity", "{{ $rarityKey }}")'> {{ $rarity }}
											<span class='checkbox-indicator'></span>
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
