@extends('app', [
	'active' => '/compendium',
	'js' => [
		'pages/compendium'
	]
])

@section('topContent')
	<div class='minor-media mb-3' hidden>
		<img src='/assets/{{ config('game.slug') }}/cover.jpg' alt='{{ config('game.data.name') }}'>
		<div class='text'>
			<h1>Compendium</h1>
		</div>
	</div>
@endsection

@section('content')


			<div class='row'>

				<!-- Products -->
				<div class='col-md-9 order-md-2'>

					<!-- Shop Grid -->
					<div class='card card--clean'>
						<header class='card__header card__header--shop-filter'>

							<div class='shop-filter'>
								<h5 class='shop-filter__result'>Showing 12 of 98 products</h5>
								<ul class='shop-filter__params'>
									<li class='shop-filter__control'>
										<select class='form-control input-xs'>
											<option>Name: A-Z</option>
											<option>Name: Z-A</option>
											<option>iLv: Low to High</option>
											<option>iLv: High to Low</option>
										</select>
									</li>
									<li class='shop-filter__control'>
										<select class='form-control input-xs'>
											<option>Show 12 per page</option>
											<option>Show 24 per page</option>
											<option>Show 36 per page</option>
										</select>
									</li>
								</ul>
							</div>

						</header>
						<div class='card__content'>

							<!-- Products -->
							<ul class='products products--grid products--grid-3 products--grid-simple'>

								<!-- Product #0 -->
								<li class='product__item'>

									<div class='product__img'>
										<div class='product__thumb'>
											<img src='assets/images/esports/samples/product-1.jpg' alt=''>
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
								<!-- Product #0 / End -->

							</ul>
							<!-- Products / End -->
						</div>
					</div>
					<!-- Shop Grid / End -->

					<!-- Shop Pagination -->
					<nav class='shop-pagination' aria-label='Shop navigation'>
						<ul class='pagination pagination--circle justify-content-center'>
							<li class='page-item'><a class='page-link' href='#'><i class='fa fa-angle-left'></i></a></li>
							<li class='page-item active'><a class='page-link' href='#'>1</a></li>
							<li class='page-item'><a class='page-link' href='#'><i class='fa fa-angle-right'></i></a></li>
						</ul>
					</nav>
					<!-- Shop Pagination / End -->

				</div>
				<!-- Products / End -->

				<!-- Sidebar -->
				<div class='sidebar sidebar--shop col-md-3 order-md-1'>

			<div class='dropdown-menu'>
				<a class='dropdown-item' href='#' data-filter='ilvl' data-type='range' data-keys='ilvlMin,ilvlMax' data-text='iLv' data-min='1' data-max='{{ $ilvlMax }}'>
					<i class='fas fa-info'></i>
					Item Level
				</a>
				<a class='dropdown-item' href='#' data-filter='rarity' data-type='rarity'>
					<i class='fas fa-registered'></i>
					Rarity
				</a>
				<a class='dropdown-item' href='#' data-filter='recipes' data-type='enabled'>
					<i class='fas fa-utensil-spoon'></i>
					Only Recipes
				</a>
				<a class='dropdown-item' href='#' data-filter='rlevel' data-type='range' data-keys='rlvlMin,rlvlMax' data-text='Level' data-min='1' data-max='{{ $rlvlMax }}'>
					<i class='fas fa-award'></i>
					Recipe Level
				</a>
				<a class='dropdown-item' href='#' data-filter='rclass' data-type='rclass'>
					<i class='fas fa-chess-bishop'></i>
					Recipe Class
				</a>
				<a class='dropdown-item' href='#' data-filter='sublevel' data-type='single' data-text='Difficulty' data-min='1' data-max='{{ config('game.maxDifficulty') }}' data-list='{{ implode(',', range(1, config('game.maxDifficulty'))) }}'>
					<i class='fas fa-star'></i>
					Recipe Difficulty
				</a>
				<a class='dropdown-item' href='#' data-filter='equipment' data-type='enabled'>
					<i class='fas fa-tshirt'></i>
					Only Equipment
				</a>
				<a class='dropdown-item' href='#' data-filter='elevel' data-type='range' data-keys='elvlMin,elvlMax' data-text='Level' data-min='1' data-max='{{ $elvlMax }}'>
					<i class='fas fa-medal'></i>
					Equip Level
				</a>
				<a class='dropdown-item' href='#' data-filter='eclass' data-type='eclass'>
					<i class='fas fa-chess-rook'></i>
					Equipment Class
				</a>
				<a class='dropdown-item' href='#' data-filter='slot' data-type='slot' data-text='Slot'>
					<i class='fas fa-hand-paper'></i>
					Equipment Slot
				</a>
				<a class='dropdown-item' href='#' data-filter='materia' data-type='single' data-text='# of Sockets' data-min='1' data-max='{{ config('game.maxSockets') }}' data-list='{{ implode(',', range(1, config('game.maxSockets'))) }}'>
					<i class='fas fa-gem'></i>
					Materia Sockets
				</a>
				<div class='dropdown-divider'></div>
				<a class='dropdown-item' href='#' data-filter='clear'>
					<i class='fas fa-trash-alt'></i>
					Clear Filters
				</a>
			</div>

					<aside class='widget card widget--sidebar widget_filter-chapter'>
						<form action='#' class='filter-chapter-form'>
							<div class='widget__title card__header card__header--has-btn'>
								<h4>
									<i class='fas fa-book'></i>
									Chapter
								</h4>
							</div>
							<div class='widget__content card__content'>
								<div class='row'>
									<div class='col-md-6'>
										<div class='form-group form-group--xs my-2'>
											<label class='radio radio-inline'>
												<input type='radio' id='chapter-items' value='items' checked> Items
												<span class='radio-indicator'></span>
											</label>
										</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group form-group--xs my-2'>
											<label class='radio radio-inline'>
												<input type='radio' id='chapter-recipes' value='recipes'> Recipes
												<span class='radio-indicator'></span>
											</label>
										</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group form-group--xs my-2'>
											<label class='radio radio-inline'>
												<input type='radio' id='chapter-equipment' value='equipment'> Equipment
												<span class='radio-indicator'></span>
											</label>
										</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group form-group--xs my-2'>
											<label class='radio radio-inline'>
												<input type='radio' id='chapter-quests' value='quests'> Quests
												<span class='radio-indicator'></span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</form>
					</aside>

					<aside class='widget card widget--sidebar widget-filter-ilvl' data-keys='ilvlMin,ilvlMax' data-min='1' data-max='{{ $ilvlMax }}'>
						<form action='#' class='filter-ilvl-form'>
							<div class='widget__title card__header card__header--has-btn'>
								<h4>Item Level</h4>
								<button class='btn btn-default btn-xs card-header__button'>Filter</button>
							</div>
							<div class='widget__content card__content'>
								<div class='slider-range-wrapper'>
									<div id='slider-range' class='slider-range'></div>
									<div class='slider-range-label'>
										iLv: <span id='slider-range-value-min'></span> - <span id='slider-range-value-max'></span>
									</div>
								</div>
							</div>
						</form>
					</aside>

					<!-- Widget: Color Filter -->
					<aside class='widget card widget--sidebar widget_color-picker'>
						<form action='#' class='color-picker-form'>
							<div class='widget__title card__header card__header--has-btn'>
								<h4>Filter by Color</h4>
								<button class='btn btn-default btn-xs card-header__button'>Filter</button>
							</div>
							<div class='widget__content card__content'>

								<ul class='filter-color'>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_1' value='1' class='color-violet'>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_2' value='2' class='color-blue' checked>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_3' value='3' class='color-light-blue'>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_4' value='4' class='color-cyan'>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_5' value='5' class='color-aqua'>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_6' value='6' class='color-green'>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_7' value='7' class='color-yellow'>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_8' value='8' class='color-orange'>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_9' value='9' class='color-red' checked>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_10' value='10' class='color-black' checked>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
									<li class='filter-color__item'>
										<label class='checkbox'>
											<input type='checkbox' id='product_color_11' value='11' class='color-white'>
											<span class='checkbox-indicator'></span>
										</label>
									</li>
								</ul>
							</div>
						</form>
					</aside>
					<!-- Widget: Color Filter / End -->

					<!-- Widget: Filter Size -->
					<aside class='widget card widget--sidebar widget_filter-size'>
						<form action='#' class='filter-size-form'>
							<div class='widget__title card__header card__header--has-btn'>
								<h4>Filter by Size</h4>
								<button class='btn btn-default btn-xs card-header__button'>Filter</button>
							</div>
							<div class='widget__content card__content'>
								<div class='row'>
									<div class='col-md-6'>
										<div class='form-group form-group--xs'>
											<label class='checkbox checkbox-inline'>
												<input type='checkbox' id='size-sm' value='1'> Small
												<span class='checkbox-indicator'></span>
											</label>
										</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group form-group--xs'>
											<label class='checkbox checkbox-inline'>
												<input type='checkbox' id='size-l' value='3'> Large
												<span class='checkbox-indicator'></span>
											</label>
										</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group form-group--xs'>
											<label class='checkbox checkbox-inline'>
												<input type='checkbox' id='size-m' value='2' checked> Medium
												<span class='checkbox-indicator'></span>
											</label>
										</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group form-group--xs'>
											<label class='checkbox checkbox-inline'>
												<input type='checkbox' id='size-xl' value='3'> Extra Large
												<span class='checkbox-indicator'></span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</form>
					</aside>
					<!-- Widget: Filter Size / End -->

					<!-- Widget: Filter Price -->
					<aside class='widget card widget--sidebar widget-filter-price'>
						<form action='#' class='filter-price-form'>
							<div class='widget__title card__header card__header--has-btn'>
								<h4>Filter by Price</h4>
								<button class='btn btn-default btn-xs card-header__button'>Filter</button>
							</div>
							<div class='widget__content card__content'>

								<div class='slider-range-wrapper'>
									<div id='slider-range' class='slider-range'></div>
									<div class='slider-range-label'>
										Price: $<span id='slider-range-value-min'></span> - $<span id='slider-range-value-max'></span>
									</div>
								</div>

							</div>
						</form>
					</aside>
					<!-- Widget: Filter Price / End -->

				</div>
				<!-- Sidebar / End -->

			</div>


	<div id='filtration'>
		<span id='chapter-select' class='mr-1'>
			<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
				<i class='fas fa-bookmark'></i> <span class='d-none d-md-inline-block'>Chapter:&nbsp;</span><span id='chapter'>Items</span>
			</button>
			<div class='dropdown-menu'>
				<a class='dropdown-item active' href='#' data-chapter='items'>Items</a>
				{{-- <a class='dropdown-item' href='#' data-chapter='quest'>Quests</a> --}}
				{{-- <a class='dropdown-item' href='#' data-chapter='locations'>Locations</a> --}}
			</div>
		</span>
		<div id='name-filter' class='mr-1'>
			<div class='input-group'>
				<input type='text' class='form-control' name='name' placeholder='Search' aria-label='Search'>
				<div class='input-group-append'>
					<button type='button' class='btn btn-outline-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopoup='true' aria-expanded='false'><i class='fas fa-sort-alpha-down'></i></button>
					<div class='dropdown-menu'>
						<a class='dropdown-item active' href='#' data-toggle='tooltip' title='Name: A-Z' data-sorting='name' data-ordering='asc'><i class='fas fa-sort-alpha-down'></i></a>
						<a class='dropdown-item' href='#' data-toggle='tooltip' title='Name: Z-A' data-sorting='name' data-ordering='desc'><i class='fas fa-sort-alpha-up'></i></a>
						<a class='dropdown-item' href='#' data-toggle='tooltip' title='iLv: Lowest First' data-sorting='ilvl' data-ordering='asc'><i class='fas fa-sort-numeric-down'></i></a>
						<a class='dropdown-item' href='#' data-toggle='tooltip' title='iLv: Highest First' data-sorting='ilvl' data-ordering='desc'><i class='fas fa-sort-numeric-up'></i></a>
					</div>
				</div>
			</div>
		</div>
		<div id='select-filters'>
			<button type='button' class='btn btn-link' data-toggle='dropdown' aria-haspopoup='true' aria-expanded='false'>
				<i class='fas fa-filter'></i>
			</button>
			<div class='dropdown-menu'>
				<a class='dropdown-item' href='#' data-filter='ilvl' data-type='range' data-keys='ilvlMin,ilvlMax' data-text='iLv' data-min='1' data-max='{{ $ilvlMax }}'>
					<i class='fas fa-info'></i>
					Item Level
				</a>
				<a class='dropdown-item' href='#' data-filter='rarity' data-type='rarity'>
					<i class='fas fa-registered'></i>
					Rarity
				</a>
				<a class='dropdown-item' href='#' data-filter='recipes' data-type='enabled'>
					<i class='fas fa-utensil-spoon'></i>
					Only Recipes
				</a>
				<a class='dropdown-item' href='#' data-filter='rlevel' data-type='range' data-keys='rlvlMin,rlvlMax' data-text='Level' data-min='1' data-max='{{ $rlvlMax }}'>
					<i class='fas fa-award'></i>
					Recipe Level
				</a>
				<a class='dropdown-item' href='#' data-filter='rclass' data-type='rclass'>
					<i class='fas fa-chess-bishop'></i>
					Recipe Class
				</a>
				<a class='dropdown-item' href='#' data-filter='sublevel' data-type='single' data-text='Difficulty' data-min='1' data-max='{{ config('game.maxDifficulty') }}' data-list='{{ implode(',', range(1, config('game.maxDifficulty'))) }}'>
					<i class='fas fa-star'></i>
					Recipe Difficulty
				</a>
				<a class='dropdown-item' href='#' data-filter='equipment' data-type='enabled'>
					<i class='fas fa-tshirt'></i>
					Only Equipment
				</a>
				<a class='dropdown-item' href='#' data-filter='elevel' data-type='range' data-keys='elvlMin,elvlMax' data-text='Level' data-min='1' data-max='{{ $elvlMax }}'>
					<i class='fas fa-medal'></i>
					Equip Level
				</a>
				<a class='dropdown-item' href='#' data-filter='eclass' data-type='eclass'>
					<i class='fas fa-chess-rook'></i>
					Equipment Class
				</a>
				<a class='dropdown-item' href='#' data-filter='slot' data-type='slot' data-text='Slot'>
					<i class='fas fa-hand-paper'></i>
					Equipment Slot
				</a>
				<a class='dropdown-item' href='#' data-filter='materia' data-type='single' data-text='# of Sockets' data-min='1' data-max='{{ config('game.maxSockets') }}' data-list='{{ implode(',', range(1, config('game.maxSockets'))) }}'>
					<i class='fas fa-gem'></i>
					Materia Sockets
				</a>
				<div class='dropdown-divider'></div>
				<a class='dropdown-item' href='#' data-filter='clear'>
					<i class='fas fa-trash-alt'></i>
					Clear Filters
				</a>
			</div>
		</div>
	</div>

	<div id='filters' class='mb-3' hidden>
		<span class='filter -enabled'>
			<button type='button' class='delete'><i class='fas fa-trash-alt'></i></button>
			<button type='button' class='btn btn-default'>
				@spaceless
				<i class='filter-icon'></i>
				<label class='filter-label'></label>
				<input type='hidden' name='' value='true'>
				@endspaceless
			</button>
		</span>
		<span class='filter -single'>
			<button type='button' class='delete'><i class='fas fa-trash-alt'></i></button>
			<button type='button' class='btn btn-default'>
				<i class='filter-icon'></i>
				<label class='filter-label'></label>
				<span class='values'>
					<input type='number' name='' placeholder='#' min='' max= data-type='special'''>
				</span>
			</button>
		</span>
		<span class='filter -range'>
			<button type='button' class='delete'><i class='fas fa-trash-alt'></i></button>
			<button type='button' class='btn btn-default'>
				<i class='filter-icon'></i>
				<label class='filter-label'></label>
				<span class='values'>
					<input type='number' name='' placeholder='&le;' min='' max=''>
					<i class='fas fa-exchange-alt reveal'></i>
					<input type='number' name='' placeholder='&ge;' min='' max=''>
				</span>
			</button>
		</span>
		<span class='filter -special -eclass'>
			<button type='button' class='delete'><i class='fas fa-trash-alt'></i></button>
			<button type='button' class='btn btn-default'>
				<i class='filter-icon'></i>
				<label class='filter-label'></label>
				<span class='values'>
					<img src='/assets/{{ config('game.slug') }}/jobs/GLA.png' alt='' width='20' height='20' hidden><span class='multiple' hidden>✚</span><i class='fa fa-edit text-muted waiting-icon'></i>
				</span>
			</button>
			<div class='extra'>
				@foreach ($jobs as $jobType => $jobTiers)
				@foreach ($jobTiers as $jobTier => $jobSet)
				<div data-type='{{ $jobType }}'>
					@foreach ($jobSet->sortBy('id') as $job)
					<span>
						<input type='checkbox' name='eclass[]' value='{{ $job->id }}' id='eclassId{{ $job->id }}' hidden>
						<label class='badge' data-toggle='tooltip' title='{{ $job->name }}' for='eclassId{{ $job->id }}'>
							<img src='/assets/{{ config('game.slug') }}/jobs/{{ $job->abbreviation }}.png' alt='{{ $job->abbreviation }}' width='24' height='24'>
						</label>
					</span>
					@endforeach
				</div>
				@endforeach
				@endforeach
			</div>
		</span>
		<span class='filter -special -rclass'>
			<button type='button' class='delete'><i class='fas fa-trash-alt'></i></button>
			<button type='button' class='btn btn-default'>
				<i class='filter-icon'></i>
				<label class='filter-label'></label>
				<span class='values'>
					<img src='/assets/{{ config('game.slug') }}/jobs/CRP.png' alt='' width='20' height='20' hidden><span class='multiple' hidden>✚</span><i class='fa fa-edit text-muted waiting-icon'></i>
				</span>
			</button>
			<div class='extra'>
				@foreach ($jobs['crafting'] as $jobTier => $jobSet)
				<div data-type='crafting'>
					@foreach ($jobSet->sortBy('id') as $job)
					<span>
						<input type='checkbox' name='rclass[]' value='{{ $job->id }}' id='rclassId{{ $job->id }}' hidden>
						<label class='badge' data-toggle='tooltip' title='{{ $job->name }}' for='rclassId{{ $job->id }}'>
							<img src='/assets/{{ config('game.slug') }}/jobs/crafting-{{ $job->abbreviation }}.png' alt='{{ $job->abbreviation }}' width='24' height='24'>
						</label>
					</span>
					@endforeach
				</div>
				@endforeach
			</div>
		</span>
		<span class='filter -special -slot'>
			<button type='button' class='delete'><i class='fas fa-trash-alt'></i></button>
			<button type='button' class='btn btn-default'>
				<i class='filter-icon'></i>
				<label class='filter-label'></label>
				<span class='values'>
					<img src='/assets/{{ config('game.slug') }}/slots/secondary.png' alt='' width='20' height='20' hidden><span class='multiple' hidden>✚</span><i class='fa fa-edit text-muted waiting-icon'></i>
				</span>
			</button>
			<div class='extra'>
				<div>
					@foreach (collect(config('game.equipmentLayout'))->unique() as $name => $key)
					<span>
						<input type='checkbox' name='slot[]' value='{{ $key }}' id='slotKey{{ $key }}' hidden>
						<label class='badge' data-toggle='tooltip' title='{{ $name }}' for='slotKey{{ $key }}'>
							<img src='/assets/{{ config('game.slug') }}/slots/{{ $key }}.png' alt='{{ $name }}' width='24' height='24'>
						</label>
					</span>
					@endforeach
				</div>
			</div>
		</span>
		<span class='filter -special -rarity'>
			<button type='button' class='delete'><i class='fas fa-trash-alt'></i></button>
			<button type='button' class='btn btn-default'>
				<i class='filter-icon'></i>
				<label class='filter-label'></label>
				<span class='values'>
					<i class='fas fa-dice-one' hidden></i><span class='multiple' hidden>✚</span><i class='fa fa-edit text-muted waiting-icon'></i>
				</span>
			</button>
			<div class='extra'>
				<div>
					@foreach (config('game.rarity') as $key => $name)
					<span>
						<input type='checkbox' name='rarity[]' value='{{ $key }}' id='rarityKey{{ $key }}' hidden>
						<label data-toggle='tooltip' title='{{ $name }}' for='rarityKey{{ $key }}'>
							<i class='fas fa-dice-{{ [ 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six' ][$loop->iteration] }} rarity-{{ $key }}'></i>
						</label>
					</span>
					@endforeach
				</div>
			</div>
		</span>
	</div>

	<div id='pre-results' class='jumbotron'>
		<h1 class='display-4'>What are you looking for?</h1>
		<p class='lead mt-4 mb-0'>Select a <i class='fas fa-bookmark'></i> chapter and start <i class='fas fa-filter'></i> filtering!</p>
	</div>

	<div id='no-results' class='jumbotron' hidden>
		<h1 class='display-4'>No results!</h1>
		<p class='lead mt-4 mb-0'>Tweak those <i class='fas fa-filter'></i> filters!</p>
	</div>

	<div id='results'>
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
	</div>

	<nav id='resultsPagination' aria-label='Pagination' class='mt-4'>

		{{-- <button type='button' class='btn btn-secondary large-view float-left'>
			<i class='fas fa-th-large -enable'></i>
			<i class='fas fa-th -disable' hidden></i>
		</button> --}}

		<ul class='pagination justify-content-end'>
			<li class='page-item disabled'>
				<a class='page-link -prev' href='#' tabindex='-1' aria-label='Previous'>
					<span aria-hidden='true'><i class='fas fa-angle-left'></i></span>
					<span class='sr-only'>Previous</span>
				</a>
			</li>
			<li class='page-item'>
				<span class='page-link text-muted'>
					Page <span id='pageNumber'>1</span>
				</span>
			</li>
			<li class='page-item disabled'>
				<a class='page-link -next' href='#' tabindex='-1' aria-label='Next'>
					<span aria-hidden='true'><i class='fas fa-angle-right'></i></span>
					<span class='sr-only'>Next</span>
				</a>
			</li>
		</ul>
	</nav>

@endsection
