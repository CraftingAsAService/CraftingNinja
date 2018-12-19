@extends('app', [
	'active' => '/knapsack',
	'js' => [
		'pages/knapsack',
		'components/my-lists'
	]
])

@section('topContent')
	<div class='minor-media mb-3' hidden>
		<img src='/assets/{{ config('game.slug') }}/cover.jpg' alt='{{ config('game.data.name') }}'>
		<div class='text'>
			<h1>Knapsack</h1>
		</div>
	</div>
@endsection

@section('content')

	<div class='row'>
		<div class='col'>
			<div class='knapsack' data-id='active'>
				<div class='compendium-item -header media mb-2'>
					<div class='media-body'>
						<i class='fas fa-check text-primary float-right saved-icon'></i>
						<h6 class='name'>Active List</h6>
					</div>
				</div>
				@if (isset($contents['item']))
				@foreach ($contents['item']['data'] as $data)
				<div class='compendium-item media mb-2' data-type='item' data-id='{{ $data['id'] }}'>
					<img src='/assets/{{ config('game.slug') }}/item/{{ $data['icon'] }}.png' alt='' width='24' height='24'>
					<div class='media-body'>
						<h6 class='name rarity-{{ $data['rarity'] }}'>{!! $data['name'] !!}</h6>
					</div>
					<div class='input'>
						<input type='number' class='form-control' size='2' placeholder='#' value='{{ $contents['item']['qtys'][$data['id']] }}'>
					</div>
					<div class='button'>
						<button type='button' class='btn btn-light delete'>
							<i class='fas fa-trash-alt'></i>
						</button>
					</div>
				</div>
				@endforeach
				@endif

				@if (empty($contents))
				<div class='compendium-item -plain media mb-2'>
					<div class='media-body'>
						<h6 class='name'>No Active List</h6>
						<div class='secondary'>
							Add from the <a href='/compendium'>Compendium</a> or edit a Published List.
						</div>
					</div>
				</div>
				@else
				<div class='text-right'>
					<button class='btn btn-link' title='Empty List' data-toggle='popover' data-placement='top' data-content='<button class="btn btn-danger" id="emptyList">Yes, Clear Active List</button>'>
						<i class='fas fa-trash-alt'></i> Clear
					</button>
					@if (auth()->check())
					<button class='btn btn-secondary' data-toggle='tooltip' title='Publish List' id='publishList'>
						<i class='fas fa-upload'></i> Save
					</button>
					@endif
					<a href='/crafting/from-list' class='btn btn-primary' data-toggle='tooltip' title='Craft List'>
						<i class='fas fa-magic'></i> Craft
					</a>
				</div>
				@if (auth()->check())
				<div id='publish' class='card bg-light my-3' hidden>
					<form method='post' action='/knapsack/publish'>
						{!! csrf_field() !!}
						<div class='card-header'>
							<h5 class='card-title mb-0'>Save List</h5>
						</div>
						<div class='card-body'>
							<div class='form-group'>
								<label>Name</label>
								<input type='text' class='form-control' name='name' value='{{ old('name') }}' required>
							</div>
							<div class='form-group'>
								<label>Description</label>
								<input type='text' class='form-control' name='description' value='{{ old('description') }}'>
							</div>
							<div class='form-group'>
								<div class='form-check'>
									<input type='checkbox' class='form-check-input' name='public' value='true' id='makePublic' {{ old('public') || ! old('name') ? ' checked' : '' }}>
									<label class='form-check-label' for='makePublic'>
										Public
									</label>
								</div>
							</div>
							<button type='submit' class='btn btn-primary' id='publish'>
								<i class='fas fa-upload'></i> Publish
							</button>
						</div>
					</form>
				</div>
				@endif
				@endif
			</div>
		</div>
		<div class='col'>
			<div class='compendium-item -header media mb-2'>
				<div class='media-body'>
					<h6 class='name'>Your Lists</h6>
				</div>
			</div>

			@if ( ! auth()->check())

			<div class='compendium-item -plain media mb-2'>
				<div class='media-body'>
					<h6 class='name'><i class='far fa-user-circle'></i> Sign In!</h6>
					<div class='secondary'>
						Sign in or create an account to save and manage your lists!
					</div>
				</div>
			</div>

			@else

			<div class='my-lists'>
				<div class='compendium-item -plain media mb-2' v-if='noResults' v-cloak>
					<div class='media-body'>
						<h6 class='name'>No Published Lists</h6>
						<div class='secondary'>
							Help your fellow crafters and publish a list!
						</div>
					</div>
				</div>
				<div class='compendium-item -plain media mb-2' v-for='(list, key) in results' v-cloak>
					<div class='media-body'>
						<h6 class='name' v-html='list.name'></h6>
						<div class='secondary'>
							<span v-if='list.description' v-html='list.description'></span>
							<div class='text-muted' v-if='list.private'>Private</div>
						</div>
					</div>
				</div>
			</div>

			@endif

			{{-- <div class='compendium-item -plain media mb-2'>
				<div class='media-body'>
					<h6 class='name'>Support CaaS on Patreon!</h6>
				</div>
				<div class='button' style='max-height: 36px;'>
					<a href='https://www.patreon.com/bePatron?u=954057' data-patreon-widget-type='become-patron-button'>Become A Patron!</a><script async src="https://c6.patreon.com/becomePatronButton.bundle.js"></script>
				</div>
			</div> --}}


		</div>
	</div>

@endsection
