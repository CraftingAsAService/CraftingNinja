<aside class='widget card widget--sidebar -filter' data-key='{{ $filter }}' data-type='{{ $type }}' v-show='["{!! $for !!}"].includes(chapter) && activeFilters.includes("{{ $filter }}")'>
	<form action='#'>
		<div class='widget__title card__header card__header--has-btn'>
			<h4>
				<i class='fas {{ $icon }} mr-1'></i>
				{{ $title }}
			</h4>
			<button class='btn btn-link btn-xs card-header__button' data-toggle='tooltip' title='Apply Changes'><i class='fa fa-check-circle'></i></button>
			<button class='btn btn-link btn-xs card-header__button mr-2' data-toggle='tooltip' title='Sweep away filter'><i class='fas fa-broom'></i></button>
		</div>
		<div class='widget__content card__content'>
			{{ $slot }}
		</div>
	</form>
</aside>
