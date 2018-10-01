@extends('app')

@section('topContent')
	<div class='major-media mb-3'>
		<img src='/assets/{{ config('game.slug') }}/cover.jpg' alt='{{ config('game.data.name') }}'>
		<div class='text'>
			<h1>{{ config('game.data.name') }}</h1>
			<h4>{{ config('game.data.description') }}</h4>
		</div>
	</div>
@endsection

@section('content')
	<h3 class='text-muted mb-3'>Let's get started!</h3>

	<div class='card-group'>
		<div class='card'>
			<div class='card-header'>
				<h5 class='card-title mb-0'>
					<i class='fas fa-book'></i>
					Compendium
				</h5>
			</div>
			<div class='card-img pt-3 pl-5 pr-5'>
				<a href='/compendium'><img class='' src='/images/tools/book-cover.svg' alt=''></a>
			</div>
			<div class='card-body'>
				<p class='card-text'>Find items, monsters, quests, everything you could want.</p>
			</div>
			<div class='card-footer text-right'>
				<a href='/compendium' class='btn btn-primary'>Take a look!</a>
			</div>
		</div>
		<div class='card'>
			<div class='card-header'>
				<h5 class='card-title mb-0'>
					<i class='fas fa-shopping-bag'></i>
					Knapsack
				</h5>
			</div>
			<div class='card-img pt-3 pl-5 pr-5'>
				<a href='/knapsack'><img class='' src='/images/tools/swap-bag.svg' alt=''></a>
			</div>
			<div class='card-body'>
				<p class='card-text'>Things from the Compendium are added to your Knapsack.</p>
			</div>
			<div class='card-footer text-right'>
				<a href='/knapsack' class='btn btn-primary'>Peer Inside!</a>
			</div>
		</div>
		<div class='card'>
			<div class='card-header'>
				<h5 class='card-title mb-0'>
					<i class='fas fa-shield-alt'></i>
					Equipment Profiler
				</h5>
			</div>
			<div class='card-img pt-3 pl-5 pr-5'>
				<a href='/equipment'><img class='' src='/images/tools/battle-gear.svg' alt=''></a>
			</div>
			<div class='card-body'>
				<p class='card-text'>What level are you?  Calculate your best gear!</p>
			</div>
			<div class='card-footer text-right'>
				<a href='/equipment' class='btn btn-primary'>Steel Thyself!</a>
			</div>
		</div>
		<div class='card'>
			<div class='card-header'>
				<h5 class='card-title mb-0'>
					<i class='fas fa-magic'></i>
					Crafting Calculator
				</h5>
			</div>
			<div class='card-img pt-3 pl-5 pr-5'>
				<a href='/crafting'><img class='' src='/images/tools/crafting.svg' alt=''></a>
			</div>
			<div class='card-body'>
				<p class='card-text'>Skip the compendium and pick from user-submitted crafting lists!</p>
			</div>
			<div class='card-footer text-right'>
				<a href='/crafting' class='btn btn-primary'>Start Crafting!</a>
			</div>
		</div>
	</div>
@endsection
