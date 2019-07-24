@component('game.compendium.widget', config('crafting.filters.all')['rarity'])
	<div class='row'>
		@foreach (config('game.rarity') as $rarityKey => $rarity)
			<div class='col-md-{{ $loop->first ? 12 : 6 }}'>
				<div class='form-group form-group--xs mb-2'>
					<label class='checkbox checkbox-inline' style='color: var(--rarity{{ $rarityKey }});'>
						<input type='checkbox' id='rarity-{{ $rarityKey }}' v-on:input='toggleFilter("rarity", "{{ $rarityKey }}")'> {{ $rarity }}
						<span class='checkbox-indicator'></span>
					</label>
				</div>
			</div>
		@endforeach
	</div>
@endcomponent
