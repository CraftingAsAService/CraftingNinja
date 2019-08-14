@component('game.compendium.widget', config('crafting.filters.all')['badditional'])
	<div class='row'>
		<div class='col-md-12'>
			<div class='form-group form-group--xs mb-2'>
				<div class='row'>
					<div class='col'>
						<input type='text' class='form-control' v-model='filters.author' v-on:input='debouncedSearch' placeholder='Author ID'>
					</div>
					<div class='col-auto'>

					</div>
					<div class='col'>

					</div>
				</div>
			</div>
		</div>
	</div>
@endcomponent
