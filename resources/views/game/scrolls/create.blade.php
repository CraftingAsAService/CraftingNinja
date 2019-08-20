@extends('app')

@section('content')

<div class='container'>
	<div class='row justify-content-center'>
		<div class='col-md-8'>
			<div class='card'>
				<div class='card__header'>
					<h4>
						{{ __('PUBLISH YOUR SCROLL') }}
					</h4>
				</div>
				<div class='card__content'>
					<div class='alc-inventory'>
						<div class='form-group'>
							<label for='name'>Your Scroll's Inventory</label>
						</div>
						<ul class='alc-inventory__list list-unstyled'>
							@foreach ($ninjaCart as $entity)
							<li class='alc-inventory__item -small'>
								<figure class='alc-inventory__item-thumb'>
									<img src='/assets/{{ config('game.slug') }}/item/{{ $entity['icon'] }}.png' alt=''>
								</figure>
								@if ($entity['quantity'] > 1)
								<div class='alc-inventory__item-badges'>
									<span class='badge badge-primary' role='info'>{{ $entity['quantity'] }}</span>
								</div>
								@endif
							</li>
							@endforeach
						</ul>
					</div>
				</div>
				<div class='card__content'>
					<form method='post' action='/scrolls'>
						@csrf
						<div class='form-group'>
							<label for='name'>Your Scroll's Title <span class='required'>*</span></label>
							<input type='text' name='name' id='name' class='form-control' placeholder='Archer&#39;s Equipment, Levels 5-10...'>
						</div>
						<div class='row'>
							<div class='col-6'>
								<div class='form-group'>
									<label for='description'>Scroll Description</label>
									<textarea name='description' rows='5' class='form-control' placeholder='Enter a description here...'></textarea>
								</div>
							</div>
							<div class='col-6'>
								<div class='form-group'>
									<label for='job'>Tag a Job and Level Range (optional)</label>
									<select name='job_id' id='job' class='form-control'>
										<option value=''>All Jobs</option>
										@foreach (['crafting', 'gathering', 'battle'] as $jobType)
											<optgroup label='{{ ucwords($jobType) }}'>
												@foreach ($jobs[$jobType] as $jobTier => $jobSet)
												@foreach ($jobSet->sortBy('id') as $job)
													<option value='{{ $job->id }}'>{{ $job->name }}</option>
												@endforeach
												@endforeach
											</optgroup>
										@endforeach
									</select>
								</div>
								<div class='row'>
									<div class='col-6'>
										<input type='number' name='min_level' id='min_level' class='form-control' placeholder='Min Level' min='1'>
									</div>
									<div class='col-6'>
										<input type='number' name='max_level' id='max_level' class='form-control' placeholder='Max Level' min='1'>
									</div>
								</div>
							</div>
						</div>

						<div class='form-group form-group--submit text-right'>
							<a href='/sling' class='btn btn-default mr-3'>Wait, Go Back</a>
							<button type='submit' class='btn btn-primary-inverse'>Publish Your Scroll</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
