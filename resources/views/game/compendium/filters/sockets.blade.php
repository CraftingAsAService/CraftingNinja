@component('game.compendium.widget', config('crafting.filters.all')['sockets'])
	<div class='row'>
		@foreach (range(0, config('game.maxSockets')) as $sockets)
			<div class='col-md-6'>
				<div class='form-group form-group--xs mb-2'>
					<label class='checkbox checkbox-inline'>
						<input type='checkbox' id='sockets-{{ $sockets }}' v-on:input='toggleFilter("sockets", "{{ $sockets }}")' checked>
						<span class='checkbox-indicator'></span>
						@if ($sockets == 0)
							Socketless
						@else
							@foreach (range(1, $sockets) as $gem)
							<span class='fas fa-gem' style='font-size: 13px;'></span>
							@endforeach
						@endif
					</label>
				</div>
			</div>
		@endforeach
	</div>
@endcomponent
