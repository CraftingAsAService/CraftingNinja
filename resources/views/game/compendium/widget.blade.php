<aside class='widget card widget--sidebar -filter -{{ $filter }}' data-type='{{ $type }}' v-show='["{!! $for !!}"].includes(chapter) && activeFilters.includes("{{ $filter }}")'>
	<div class='widget__title card__header card__header--has-btn'>
		<h4>
			<i class='fas {{ $icon }} mr-1'></i>
			{{ $title }}
		</h4>
		<button type='button' class='btn btn-link btn-xs card-header__button' data-toggle='tooltip' title='Apply Changes' @click='applyFilter("{{ $filter }}")'><i class='fa fa-check-circle'></i></button>
		<button type='button' class='btn btn-link btn-xs card-header__button mr-2' data-toggle='tooltip' title='Sweep away filter' @click='removeFilter("{{ $filter }}")'><i class='fas fa-broom'></i></button>
	</div>
	<div class='widget__content card__content'>
		{{ $slot }}
	</div>
</aside>
