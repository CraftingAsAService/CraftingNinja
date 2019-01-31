<aside class='widget card widget--sidebar widget-filter-rlevel' data-type='range' data-keys='rlvlMin,rlvlMax' data-min='1' data-max='{{ $rlvlMax }}' hidden>
	<form action='#' class='filter-ilvl-form'>
		<div class='widget__title card__header card__header--has-btn'>
			<h4>
				<i class='fas fa-info mr-1'></i>
				Recipe Level
			</h4>
			<button class='btn btn-default btn-xs card-header__button'>Apply</button>
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
