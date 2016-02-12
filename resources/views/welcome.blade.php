@extends('app')

@section('vendor-css')
	<link href='//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' rel='stylesheet'>
@stop

@section('javascript')
	<script type='text/javascript' src='{!! cdn('/js/home.js') !!}'></script>
@stop

@section('banner')
	<h1>Welcome to Crafting as a Service</h1>
	<h2>Crafting information and planning for your favorite games</h2>
@stop

@section('content')

<div class='row'>
	<div class='col-sm-4 homepage-step'>
		<a href='/{{ config('gid') ? (config('game-slug') . '/compendium') : 'games?selection=compendium' }}' class='home-callout'>
			<p class='title'>
				<img src='/images/icons/compendium-black.svg' width='24' height='24'>
				Open the Compendium
			</p>
			<p class='description'>
				Add items to your Knapsack by reading a magical tome written by your peers!
			</p>
			<p class='step'>
				<img src='/images/icons/one.png' class='pull-right' width='18' height='18'>
				Begin Step
			</p>
		</a>
	</div>
	<div class='col-sm-4 homepage-step'>
		<a href='/{{ config('gid') ? (config('game-slug') . '/knapsack') : 'games?selection=knapsack' }}' class='home-callout'>
			<p class='title'>
				<img src='/images/icons/knapsack-black.svg' width='24' height='24'>
				Look in your Bag
			</p>
			<p class='description'>
				Open your Knapsack, craft those recipes or share its contents with a friend!
			</p>
			<p class='step'>
				<img src='/images/icons/two.png' class='pull-right' width='18' height='18'>
				Begin Step
			</p>
		</a>
	</div>
	<div class='col-sm-4 homepage-step'>
		<a href='/{{ config('gid') ? (config('game-slug') . '/tools') : 'games?selection=tools' }}' class='home-callout'>
			<p class='title'>
				<img src='/images/icons/tools-black.svg' width='24' height='24'>
				Craft!
			</p>
			<p class='description'>
				Use our myriad of tools to craft those Knapsack items or get equipped!
			</p>
			<p class='step'>
				<img src='/images/icons/three.png' class='pull-right' width='18' height='18'>
				Begin Step
			</p>
		</a>
	</div>
</div>

@stop