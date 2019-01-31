<aside class='widget card widget--sidebar widget-filter-ilvl' data-type='range' data-keys='ilvlMin,ilvlMax' data-min='1' data-max='{{ $ilvlMax }}' hidden>
	<form action='#' class='filter-ilvl-form'>
		<div class='widget__title card__header card__header--has-btn'>
			<h4>
				<i class='fas fa-info mr-1'></i>
				Item Level
			</h4>
			<button class='btn btn-primary btn-xs card-header__button'><i class='fas fa-check'></i></button>
			<button class='btn btn-link btn-xs card-header__button mr-2'><i class='fas fa-trash'></i></button>
		</div>
		<div class='widget__content card__content'>
			<div class='slider-range-wrapper'>
				<div id='slider-range' class='slider-range'></div>
				<div class='slider-range-label'>
					iLv: <span id='slider-range-value-min'></span> - <span id='slider-range-value-max'></span>
				</div>
			</div>
		</div>
	</form>
</aside>
