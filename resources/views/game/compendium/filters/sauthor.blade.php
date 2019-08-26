@component('game.compendium.widget', config('crafting.filters.all')['sauthor'])
	<div class='form-group form-group--xs mb-2'>
		<input type='text' class='form-control' v-model='filters.sauthor' v-on:input='debouncedSearch' placeholder='Author ID'>
	</div>
@endcomponent
