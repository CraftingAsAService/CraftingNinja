@component('game.compendium.widget', config('crafting.filters.all')['slot'])
	<ul class='filter-color'>
		@foreach (collect(config('game.equipmentLayout'))->unique() as $name => $key)
			<li class='filter-color__item'>
				<label class='checkbox' data-toggle='tooltip' title='{{ $name }}' for='slotId{{ $key }}'>
					<input type='checkbox' id='slotId{{ $key }}' v-on:input='toggleFilter("slot", "{{ $key }}")' hidden>
					<img src='/assets/{{ config('game.slug') }}/slots/{{ $key }}.png' alt='{{ $name }}' class='checkbox-indicator' width='24' height='24'>
				</label>
			</li>
		@endforeach
	</ul>
@endcomponent
