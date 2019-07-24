@component('game.compendium.widget', config('crafting.filters.all')['badditional'])
	<div class='row'>
		<div class='col-md-12'>
			<div class='form-group form-group--xs mb-2'>
				<label class='checkbox checkbox-inline'>
					<input type='checkbox' id='bmine' v-on:input='toggleFilter("badditional", "mine")'> Only Your Books
					<span class='checkbox-indicator'></span>
				</label>
			</div>
		</div>
	</div>
@endcomponent
