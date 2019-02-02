<aside class='widget card widget--sidebar widget_filter-rclass' data-type='multiple' hidden>
	<form action='#' class='color-picker-form'>
		<div class='widget__title card__header card__header--has-btn'>
			<h4>
				<i class='fas fa-chess-bishop mr-1'></i>
				Recipe Class
			</h4>
			<button class='btn btn-primary btn-xs card-header__button'><i class='fas fa-check'></i></button>
			<button class='btn btn-link btn-xs card-header__button mr-2'><i class='fas fa-trash'></i></button>
		</div>
		<div class='widget__content card__content'>
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
		</div>
	</form>
</aside>
