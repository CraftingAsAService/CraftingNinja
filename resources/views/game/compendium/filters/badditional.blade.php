@if (auth()->check())
@component('game.compendium.widget', config('crafting.filters.all')['badditional'])
	<div class='row'>
		<div class='col-md-12'>
			@if (auth()->check())
			<div class='form-group form-group--xs mb-2'>
				<label class='checkbox checkbox-inline'>
					<input type='checkbox' id='bmine' v-on:input='toggleFilter("badditional", "mine")'{{ $chapterStart == 'books' && $filterStart == 'mine' ? ' checked="checked"' : '' }}> Only Your Books
					<span class='checkbox-indicator'></span>
				</label>
			</div>
			@endif
		</div>
	</div>
@endcomponent
@endif
