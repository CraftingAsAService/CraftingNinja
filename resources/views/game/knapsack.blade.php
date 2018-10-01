@extends('app', [
	'active' => '/knapsack',
	'js' => [

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

	@if (empty($contents))
	<div id='no-results' class='jumbotron my-3'>
		<h1 class='display-4'>Nothing in your list!</h1>
		<p class='lead mt-4 mb-0'><i class='fas fa-book'></i> Go <a href='/compendium'>add something</a>!</p>
	</div>
	@endif

	<div id='knapsack'>
		<div class='row my-3'>
			<div class='col'>
				<div class='compendium-item -knapsack -header media mb-2'>
					<div class='media-body'>
						<h6 class='name'>Active List</h6>
					</div>
				</div>
				@if (isset($contents['item']))
				@foreach ($contents['item']['data'] as $data)
				<div class='compendium-item -knapsack media mb-2'>
					<img src='/assets/{{ config('game.slug') }}/item/{{ $data['icon'] }}.png' alt='' width='24' height='24'>
					<div class='media-body'>
						<h6 class='name rarity-{{ $data['rarity'] }}'>{!! $data['name'] !!}</h6>
					</div>
					<div class='input'>
						<input type='number' class='form-control' size='2' placeholder='#' value='{{ $contents['item']['qtys'][$data['id']] }}'>
					</div>
					<div class='button'>
						<button type='button' class='btn btn-light add-to-list' data-id='' data-type='item'>
							<i class='fas fa-trash-alt'></i>
						</button>
					</div>
				</div>
				@endforeach
				@endif
			</div>
			<div class='col'>
				<div class='compendium-item -knapsack -header media mb-2'>
					<div class='media-body'>
						<h6 class='name'>Saved Lists</h6>
					</div>
				</div>
				<div class='compendium-item -knapsack media mb-2'>
					<div class='media-body'>
						<h6 class='name'>Support Site on Patreon to unlock Saved Lists!</h6>
					</div>
					<div class='button' style='max-height: 36px;'>
						<a href="https://www.patreon.com/bePatron?u=954057" data-patreon-widget-type="become-patron-button">Become A Patron!</a><script async src="https://c6.patreon.com/becomePatronButton.bundle.js"></script>
					</div>
				</div>

			</div>
		</div>
	</div>

@endsection
