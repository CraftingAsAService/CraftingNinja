@component('game.compendium.widget', config('crafting.filters.all')['rlvl'])
	<div class='row'>
		<div class='col'>
			<input type='number' class='form-control min' v-model='filters.rlvlMin' min='1' max='{{ $max['rlvl'] }}' v-on:input='debouncedSearch'>
		</div>
		<div class='col-auto'>
			<i class='fas fa-exchange-alt mt-3'></i>
		</div>
		<div class='col'>
			<input type='number' class='form-control max' v-model='filters.rlvlMax' min='1' max='{{ $max['rlvl'] }}' v-on:input='debouncedSearch'>
		</div>
	</div>
@endcomponent
