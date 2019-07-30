{{-- bclass is just a masked eclass --}}
@component('game.compendium.widget', config('crafting.filters.all')['bclass'])
	<ul class='filter-color'>
		@foreach ($jobs as $jobType => $jobTiers)
		@foreach ($jobTiers as $jobTier => $jobSet)
		@foreach ($jobSet->sortBy('id') as $job)
			<li class='filter-color__item {{ $jobType }}-job'>
				<label class='checkbox' data-toggle='tooltip' title='{{ $job->name }}' for='bclassId{{ $job->id }}'>
					<input type='checkbox' id='bclassId{{ $job->id }}' v-on:input='toggleFilter("bclass", "{{ $job->id }}")' hidden>
					<img src='/assets/{{ config('game.slug') }}/jobs/{{ $job->abbreviation }}.png' class='job-icon checkbox-indicator' alt='{{ $job->abbreviation }}' width='24' height='24'>
				</label>
			</li>
		@endforeach
		@endforeach
		@endforeach
	</ul>
@endcomponent
