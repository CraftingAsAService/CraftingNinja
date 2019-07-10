@extends('app')

@section('heading')
		<!-- Page Heading
		================================================== -->
		<style type='text/css'>
			.page-heading {
				background: url('/assets/{{ config('game.slug') }}/cover.jpg');
			}
		</style>
		<div class='page-heading page-heading--horizontal page-heading--duotone'>
			<div class='container'>
				<div class='row'>
					<div class='col align-self-start'>
						<h1 class='page-heading__title'>
							@foreach (explode(' ', config('game.data.name')) as $namePiece)
								<span class='{{ $loop->index % 2 ? 'highlight' : '' }}'>{!! $namePiece !!}</span>
							@endforeach
						</h1>
					</div>
					{{-- <div class='col align-self-end'>
						<ol class='page-heading__breadcrumb breadcrumb font-italic'>
							<li class='breadcrumb-item'><a href='_esports_index.html'>Home</a></li>
							<li class='breadcrumb-item active' aria-current='page'>Shortcodes</li>
						</ol>
					</div> --}}
				</div>
			</div>
		</div>
		<!-- Page Heading / End -->
@endsection

@section('content')

	<div class='posts posts--card-compact row'>
		<div class='post-grid__item col-sm-6 col-lg-4'>
			<div class='posts__item card'>
				<figure class='posts__thumb -icon -gradient-1'>
					<a href='/compendium'><i class='fas fa-book'></i></a>
				</figure>
				<div class='posts__inner card__content'>
					<h6 class='posts__title posts__title--color-hover'><a href='/compenium'>Read The Compendium</a></h6>
					<ul class='post__meta meta'>
						<li class='meta__item'>Search for the things you need.</li>
					</ul>
				</div>
			</div>
		</div>
		<div class='post-grid__item col-sm-6 col-lg-4'>
			<div class='posts__item card'>
				<figure class='posts__thumb -icon -gradient-2'>
					<a href='/knapsack'><i class='fas fa-th'></i></a>
				</figure>
				<div class='posts__inner card__content'>
					<h6 class='posts__title posts__title--color-hover'><a href='/knapsack'>Open Your Knapsack</a></h6>
					<ul class='post__meta meta'>
						<li class='meta__item'>Manage the things you need.</li>
					</ul>
				</div>
			</div>
		</div>
		<div class='post-grid__item col-sm-6 col-lg-4'>
			<div class='posts__item card'>
				<figure class='posts__thumb -icon -gradient-3'>
					<a href='/equipment'><i class='fas fa-shield-alt'></i></a>
				</figure>
				<div class='posts__inner card__content'>
					<h6 class='posts__title posts__title--color-hover'><a href='/equipment'>Equip Thyself</a></h6>
					<ul class='post__meta meta'>
						<li class='meta__item'>Wear the things you need.</li>
					</ul>
				</div>
			</div>
		</div>
		{{-- <div class='post-grid__item col-sm-6 col-lg-4'>
			<div class='posts__item card'>
				<figure class='posts__thumb -icon -gradient-4'>
					<a href='/crafting'><i class='fas fa-magic'></i></a>
				</figure>
				<div class='posts__inner card__content'>
					<h6 class='posts__title posts__title--color-hover'><a href='/crafting'>Work your Magic</a></h6>
					<ul class='post__meta meta'>
						<li class='meta__item'>Craft the things you need.</li>
					</ul>
				</div>
			</div>
		</div> --}}
		<div class='post-grid__item col-sm-6 col-lg-4'>
			<div class='posts__item card'>
				<figure class='posts__thumb -icon -gradient-1'>
					<a href='/quests'><i class='fas fa-journal-whills'></i></a>
				</figure>
				<div class='posts__inner card__content'>

					<h6 class='posts__title posts__title--color-hover'><a href='/quests'>Adventure Awaits</a></h6>
					<ul class='post__meta meta'>
						<li class='meta__item'>Seek others for the things you need.</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
@endsection
