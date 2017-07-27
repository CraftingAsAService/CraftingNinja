@extends('app')

@section('content')

<h1>{{ config('game')->name }}</h1>

<h2>Tools</h2>

<ul>
	<li>
		<a href='/compendium'>Compendium</a>
	</li>
	<li>
		<a href='/knapsack'>Knapsack</a>
	</li>
</ul>

@stop