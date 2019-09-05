@component('game.compendium.widget', config('crafting.filters.all')['name'])
	<div class='form-group form-group--xs mb-2'>
		<input type='search' :placeholder='"Search " + chapter.substr(0, 1).toUpperCase() + chapter.substr(1, chapter.length) + "s"' class='form-control mt-1 mb-1' v-model='filters.name' v-on:input='nameUpdated'>
	</div>
@endcomponent
