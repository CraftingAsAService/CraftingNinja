{{-- TODO LAYOUT --}}

<form method='post' action='/books'>
	@csrf
	<input type='text' name='name'>
</form>
		// name
		// description
		// OPTIONAL
		// job_id
		// min_level
		// max_level
		// published_at


@foreach ($ninjaCart as $entity)
	{{ $entity['name'] }} x {{ $entity['quantity'] }}
@endforeach
