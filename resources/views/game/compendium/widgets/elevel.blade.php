<aside class='widget card widget--sidebar widget-filter-elevel' data-type='range' data-keys='elvlMin,elvlMax' data-min='1' data-max='{{ $elvlMax }}' hidden>
	<form action='#' class='filter-ilvl-form'>
		<div class='widget__title card__header card__header--has-btn'>
			<h4>
				<i class='fas fa-medal mr-1'></i>
				Equipment Level
			</h4>
			<button class='btn btn-primary btn-xs card-header__button'><i class='fas fa-check'></i></button>
			<button class='btn btn-link btn-xs card-header__button mr-2'><i class='fas fa-trash'></i></button>
		</div>
		<div class='widget__content card__content'>
			<div class='slider-range-wrapper'>
				<div class='slider-range'></div>
				<div class='slider-range-label'>
					eLv: <span class='min-value'></span> - <span class='max-value'></span>
				</div>
			</div>
		</div>
	</form>
</aside>
