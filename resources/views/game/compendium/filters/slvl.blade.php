{{-- slvl is just a masked elvl --}}
@component('game.compendium.widget', config('crafting.filters.all')['slvl'])
	<div class='row'>
		<div class='col'>
			<input type='number' class='form-control min' v-model='filters.slvlMin' min='1' max='{{ $max['elvl'] }}' v-on:input='debouncedSearch'>
		</div>
		<div class='col-auto'>
			<i class='fas fa-exchange-alt mt-3'></i>
		</div>
		<div class='col'>
			<input type='number' class='form-control max' v-model='filters.slvlMax' min='1' max='{{ $max['elvl'] }}' v-on:input='debouncedSearch'>
		</div>
	</div>
@endcomponent
