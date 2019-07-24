@component('game.compendium.widget', config('crafting.filters.all')['rdifficulty'])
	<div class='row'>
		@foreach (range(0, config('game.maxDifficulty')) as $sublevel)
			<div class='col-md-{{ $sublevel == 0 ? 12 : 6 }}'>
				<div class='form-group form-group--xs mb-2'>
					<label class='checkbox checkbox-inline'>
						<input type='checkbox' id='sublevel-{{ $sublevel }}' v-on:input='toggleFilter("sublevel", "{{ $sublevel }}")'>
						<span class='checkbox-indicator'></span>
						@if ($sublevel == 0)
							Base Difficulty
						@else
							@foreach (range(1, $sublevel) as $star)
							<span class='sublevel-icon'></span>
							@endforeach
						@endif
					</label>
				</div>
			</div>
		@endforeach
	</div>
@endcomponent
