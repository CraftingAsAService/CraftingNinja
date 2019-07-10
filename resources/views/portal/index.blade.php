@extends('app')

@section('heading')
		<!-- Page Heading
		================================================== -->
		<div class="page-heading page-heading--horizontal page-heading--duotone">
			<div class="container">
				<div class="row">
					<div class="col align-self-start">
						<h1 class="page-heading__title">Game <span class="highlight">list</span></h1>
					</div>
					{{-- <div class="col align-self-end">
						<ol class="page-heading__breadcrumb breadcrumb font-italic">
							<li class="breadcrumb-item"><a href="_esports_index.html">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Shortcodes</li>
						</ol>
					</div> --}}
				</div>
			</div>
		</div>
		<!-- Page Heading / End -->
@endsection

@section('content')


				<div class='row'>
					<!-- Content -->
					<div class='content col-lg-8'>
						<!-- Posts Area #1 -->
						<!-- Posts Grid -->
						<div class='posts posts--tile post-grid row'>
							@foreach ($games as $game)
								<div class='post-grid__item col-sm-6'>
									<div class='posts__item posts__item--tile posts__item--category-{{ $loop->index % 4 + 1 }} card'>
										<figure class='posts__thumb'>
											<a href="https://{{ $game->slug }}.{{ config('app.base_url') }}/compendium"><img src='/assets/{{ $game->slug }}/cover.jpg' alt='{{ $game->name }}'></a>
											<div class='posts__inner'>
												<h6 class='posts__title posts__title--color-hover'><a href='https://{{ $game->slug }}.{{ config('app.base_url') }}'>{{ $game->name }}</a></h6>
											</div>
										</figure>
										<a href="https://{{ $game->slug }}.{{ config('app.base_url') }}/compendium" class="posts__cta"></a>
									</div>
								</div>
							@endforeach
						</div>
						<!-- Post Grid / End -->
						<!-- Posts Area #1 / End -->
					</div>
					<!-- Content / End -->

					<!-- Sidebar -->
					<div id='sidebar' class='sidebar col-lg-4'>

						<!-- Widget: Trending News -->
						<aside class='widget widget--sidebar card'>
							<div class='widget__title card__header'>
								<h4>Crafter's Corner</h4>
							</div>
							<div class='widget__content card__content'>
								<ul class='posts posts--simple-list'>
									@foreach ($news as $entry)
										<li class='posts__item'>
											<figure class='posts__thumb posts__thumb--hover'>
												<a href='{{ $entry['url'] }}'><img src='{{ $entry['thumbnail'] ?? '/images/favicon@2x.png' }}' alt=''></a>
											</figure>
											<div class='posts__inner'>
												<div class='posts__cat'>
													@foreach($entry['flair'] as $flair)
													<span class='label posts__cat-label posts__cat-label--category-3'>{{ $flair }}</span>
													@endforeach
												</div>
												<h6 class='posts__title posts__title--color-hover'><a href='{{ $entry['url'] }}'>{!! $entry['title'] !!}</a></h6>
												<time datetime='{{ $entry['date'] }}' class='posts__date'>{{ $entry['created'] }}</time>
											</div>
										</li>
									@endforeach
								</ul>
							</div>
						</aside>
						<!-- Widget: Trending News / End -->

					</div>
					<!-- Sidebar / End -->
				</div>
@endsection
