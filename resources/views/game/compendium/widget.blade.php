<aside class='widget card widget--sidebar -filter -{{ $key }}' data-type='{{ $type }}' v-show='["{!! implode('","', $for) !!}"].includes(chapter)'>
	<div class='widget__title card__header card__header--has-btn'>
		<h4>
			<i class='fas {{ $icon }} mr-1'></i>
			{{ $title }}
		</h4>
		<button type='button' class='btn btn-link btn-xs card-header__button mr-2' @click='toggleCollapse("{{ $key }}")'><i :class='"far fa-caret-square-" + (collapsed.includes("{{ $key }}") ? "down" : "up")'></i></button>
	</div>
	<div :class='"widget__content card__content" + (collapsed.includes("{{ $key }}") ? " collapse" : "")'>
		{{ $slot }}
	</div>
</aside>
