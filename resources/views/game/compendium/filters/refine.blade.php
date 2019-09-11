@component('game.compendium.widget', config('crafting.filters.all')['refine'])
	<div class='checkbox-table'>
		@foreach (config('crafting.filters.sorting') as $key => $sorting)
			<label class='checkbox checkbox--cell -radio' data-toggle='tooltip' title='{{ $sorting['title'] }}' for='sorting{{ $key }}'>
				<input type='radio' id='sorting{{ $key }}' v-model='filters.sorting' value='{{ $key }}' hidden>
				<span class='checkbox-indicator'><i class='fas {{ $sorting['icon'] }}'></i></span>
			</label>
		@endforeach
	</div>
	<div class='checkbox-table mt-1'>
		@foreach (config('crafting.filters.perPage') as $key => $perPage)
			<label class='checkbox checkbox--cell -radio' data-toggle='tooltip' title='{{ $perPage['title'] }}' for='perPage{{ $key }}'>
				<input type='radio' id='perPage{{ $key }}' v-model='filters.perPage' value='{{ $key }}' hidden>
				<span class='checkbox-indicator'><i class='fas {{ $perPage['icon'] }}'></i></span>
			</label>
		@endforeach
	</div>
@endcomponent
