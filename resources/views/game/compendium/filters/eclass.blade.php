@component('game.compendium.widget', config('crafting.filters.all')['eclass'])
	<ul class='filter-color'>
		@foreach ($jobs as $jobType => $jobTiers)
		@foreach ($jobTiers as $jobTier => $jobSet)
		@foreach ($jobSet->sortBy('id') as $job)
			<li class='filter-color__item {{ $jobType }}-job'>
				<label class='checkbox' data-toggle='tooltip' title='{{ $job->name }}' for='eclassId{{ $job->id }}'>
					<input type='checkbox' id='eclassId{{ $job->id }}' v-on:input='toggleFilter("eclass", "{{ $job->id }}")' hidden>
					<img src='/assets/{{ config('game.slug') }}/jobs/{{ $job->icon }}.png' class='checkbox-indicator' alt='{{ $job->abbreviation }}' width='24' height='24'>
				</label>
			</li>
		@endforeach
		@endforeach
		@endforeach
	</ul>
@endcomponent
