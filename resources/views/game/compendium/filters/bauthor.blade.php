@component('game.compendium.widget', config('crafting.filters.all')['bauthor'])
	<div class='form-group form-group--xs mb-2'>
		<input type='text' class='form-control' v-model='filters.bauthor' v-on:input='debouncedSearch' placeholder='Author ID'>
	</div>
@endcomponent
