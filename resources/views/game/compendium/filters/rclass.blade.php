@component('game.compendium.widget', config('crafting.filters.all')['rclass'])
	<div class='checkbox-table'>
		@foreach ($jobs['crafting'] as $jobTier => $jobSet)
		@foreach ($jobSet->sortBy('id') as $job)
			<label class='checkbox checkbox--cell' data-toggle='tooltip' title='{{ $job->name }}' for='rclassId{{ $job->id }}'>
				<input type='checkbox' id='rclassId{{ $job->id }}' v-on:input='toggleFilter("rclass", "{{ $job->id }}")' hidden>
				<span class='checkbox-indicator'><img src='/assets/{{ config('game.slug') }}/jobs/crafting-{{ $job->abbreviation }}.png' alt='{{ $job->abbreviation }}'></span>
			</label>
		@endforeach
		@endforeach
	</ul>
@endcomponent
