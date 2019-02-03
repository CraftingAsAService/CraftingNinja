<aside class='widget card widget--sidebar widget_filter-{{ $filter }}' data-type='{{ $type }}' hidden>
	<form action='#'>
		<div class='widget__title card__header card__header--has-btn'>
			<h4>
				<i class='fas {{ $icon }} mr-1'></i>
				{{ $title }}
			</h4>
			<button class='btn btn-primary btn-xs card-header__button' data-toggle='tooltip' title='Apply Changes'><i class='fas fa-check'></i></button>
			<button class='btn btn-link btn-xs card-header__button mr-2' data-toggle='tooltip' title='Clear &amp; Remove Filter'><i class='fas fa-trash'></i></button>
		</div>
		<div class='widget__content card__content'>
			{{ $slot }}
		</div>
	</form>
</aside>
