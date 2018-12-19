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
