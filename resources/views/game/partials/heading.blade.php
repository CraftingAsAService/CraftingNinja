
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
							@php
								$title = $heading ?? config('game.data.name');
							@endphp
							@foreach (explode(' ', $title) as $namePiece)
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
