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
								<input type='radio' name='chapter' id='chapter-items' value='items' checked> Items
								<span class='radio-indicator'></span>
							</label>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group form-group--xs my-2'>
							<label class='radio radio-inline'>
								<input type='radio' name='chapter' id='chapter-recipes' value='recipes'> Recipes
								<span class='radio-indicator'></span>
							</label>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group form-group--xs my-2'>
							<label class='radio radio-inline'>
								<input type='radio' name='chapter' id='chapter-equipment' value='equipment'> Equipment
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

	{{-- Filters Defined --}}

	@php
		// Filters array used to generate both filtration anchors and filtration widgets
		$filters = [
			'ilvl' => [
				'for'    => 'items,recipes,equipment',
				'filter' => 'ilvl',
				'type'   => 'range',
				'icon'   => 'fa-info',
				'title'  => 'Item Level',
			],
			'rarity' => [
				'for'    => 'items,recipes,equipment',
				'filter' => 'rarity',
				'type'   => '',
				'icon'   => 'fa-registered',
				'title'  => 'Rarity',
			],
			'rlevel' => [
				'for'    => 'recipes',
				'filter' => 'rlevel',
				'type'   => 'range',
				'icon'   => 'fa-award',
				'title'  => 'Recipe Level',
				'hidden' => true,
			],
			'rclass' => [
				'for'    => 'recipes',
				'filter' => 'rclass',
				'type'   => 'multiple',
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
				'hidden' => true,
			],
			'elevel' => [
				'for'    => 'equipment',
				'filter' => 'elevel',
				'type'   => 'range',
				'icon'   => 'fa-medal',
				'title'  => 'Equipment Level',
				'hidden' => true,
			],
			'eclass' => [
				'for'    => 'equipment',
				'filter' => 'eclass',
				'type'   => 'multiple',
				'icon'   => 'fa-chess-rook',
				'title'  => 'Equipment Class',
				'hidden' => true,
			],
			'slot' => [
				'for'    => 'equipment',
				'filter' => 'slot',
				'type'   => 'multiple',
				'icon'   => 'fa-hand-paper',
				'title'  => 'Equipment Slot',
				'hidden' => true,
			],
			'sockets' => [
				'for'    => 'equipment',
				'filter' => 'sockets',
				'type'   => 'multiple',
				'icon'   => 'fa-gem',
				'title'  => 'Materia Sockets',
				'hidden' => true,
			],
		];
	@endphp

	{{-- Filters List/Anchors --}}

	<aside class="widget card widget--sidebar widget_categories">
		<div class="widget__title card__header card__header--has-btn">
			<h4>
				<i class='fas fa-filter'></i>
				Filter By&hellip;
			</h4>
			<button class='btn btn-link btn-xs card-header__button mr-2' data-toggle='tooltip' title='Clear All Filters'><i class='fas fa-trash'></i></button>
		</div>
		<div class="widget__content card__content">
			<ul class="widget__list">
				@foreach ($filters as $filter)
				<li data-for='{{ $filter['for'] }}' data-filter='{{ $filter['filter'] }}'{{ isset($filter['hidden']) ? ' hidden' : '' }}>
					<a href='#'>
						<i class='fas {{ $filter['icon'] }} mr-1'></i>
						{{ $filter['title'] }}
					</a>
				</li>
				@endforeach
			</ul>
		</div>
	</aside>

	{{-- Filter Widgets --}}

	@component('game.compendium.widget', $filters['ilvl'])
		<div class='slider-range-wrapper'>
			<div id='slider-range' class='slider-range'></div>
			<div class='slider-range-label'>
				iLv: <span id='slider-range-value-min'></span> - <span id='slider-range-value-max'></span>
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
		<div class='slider-range-wrapper' data-keys='rlvlMin,rlvlMax' data-min='1' data-max='{{ $rlvlMax }}'>
			<div class='slider-range'></div>
			<div class='slider-range-label'>
				rLv: <span class='min-value'></span> - <span class='max-value'></span>
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
		<div class='slider-range-wrapper' data-keys='elvlMin,elvlMax' data-min='1' data-max='{{ $elvlMax }}'>
			<div class='slider-range'></div>
			<div class='slider-range-label'>
				eLv: <span class='min-value'></span> - <span class='max-value'></span>
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
