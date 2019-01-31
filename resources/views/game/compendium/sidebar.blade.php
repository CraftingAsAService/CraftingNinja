<div class='sidebar sidebar--shop col-md-3 order-md-1'>

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
					{{-- <div class='col-md-6'>
						<div class='form-group form-group--xs my-2'>
							<label class='radio radio-inline'>
								<input type='radio' id='chapter-quests' value='quests'> Quests
								<span class='radio-indicator'></span>
							</label>
						</div>
					</div> --}}
				</div>
			</div>
		</form>
	</aside>

	<aside class="widget card widget--sidebar widget_categories">
		<div class="widget__title card__header">
			<h4>
				<i class='fas fa-filter'></i>
				Filter By&hellip;
			</h4>
		</div>
		<div class="widget__content card__content">
			<ul class="widget__list">
				<li data-for='items,recipes,equipment' data-filter='ilvl'>
					<a href='#'>
						<i class='fas fa-info mr-1'></i>
						Item Level
					</a>
				</li>
				<li data-for='items,recipes,equipment' data-filter='rarity'>
					<a href='#'>
						<i class='fas fa-registered mr-1'></i>
						Rarity
					</a>
				</li>

				<li data-for='recipes' data-filter='rlevel' hidden>
					<a href='#'>
						<i class='fas fa-award mr-1'></i>
						Recipe Level
					</a>
				</li>
				<li data-for='recipes' hidden>
					<a href='#' data-filter='rclass' data-type='rclass'>
						<i class='fas fa-chess-bishop mr-1'></i>
						Recipe Class
					</a>
				</li>
				<li data-for='recipes' hidden>
					<a href='#' data-filter='sublevel' data-type='single' data-text='Difficulty' data-min='1' data-max='{{ config('game.maxDifficulty') }}' data-list='{{ implode(',', range(1, config('game.maxDifficulty'))) }}'>
						<i class='fas fa-star mr-1'></i>
						Recipe Difficulty
					</a>
				</li>

				<li data-for='equipment' hidden>
					<a href='#' data-filter='elevel' data-type='range' data-keys='elvlMin,elvlMax' data-text='Level' data-min='1' data-max='{{ $elvlMax }}'>
						<i class='fas fa-medal mr-1'></i>
						Equip Level
					</a>
				</li>
				<li data-for='equipment' hidden>
					<a href='#' data-filter='eclass' data-type='eclass'>
						<i class='fas fa-chess-rook mr-1'></i>
						Equipment Class
					</a>
				</li>
				<li data-for='equipment' hidden>
					<a href='#' data-filter='slot' data-type='slot' data-text='Slot'>
						<i class='fas fa-hand-paper mr-1'></i>
						Equipment Slot
					</a>
				</li>
				<li data-for='equipment' hidden>
					<a href='#' data-filter='materia' data-type='single' data-text='# of Sockets' data-min='1' data-max='{{ config('game.maxSockets') }}' data-list='{{ implode(',', range(1, config('game.maxSockets'))) }}'>
						<i class='fas fa-gem mr-1'></i>
						Materia Sockets
					</a>
				</li>

				<li>
					<a href='#' data-filter='clear'>
						<i class='fas fa-trash-alt mr-1'></i>
						Clear Filters
					</a>
				</li>
			</ul>
		</div>
	</aside>

	@include('games.compendium.widgets.ilvl')

	@include('games.compendium.widgets.rarity')

	@include('games.compendium.widgets.rlevel')











	<!-- Widget: Color Filter -->
	<aside class='widget card widget--sidebar widget_color-picker'>
		<form action='#' class='color-picker-form'>
			<div class='widget__title card__header card__header--has-btn'>
				<h4>Filter by Color</h4>
				<button class='btn btn-default btn-xs card-header__button'>Apply</button>
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
				<button class='btn btn-default btn-xs card-header__button'>Apply</button>
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
				<button class='btn btn-default btn-xs card-header__button'>Apply</button>
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
