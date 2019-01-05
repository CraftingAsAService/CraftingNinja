{{-- TODO LAYOUT --}}

@foreach ($listings as $listing)

	@if ($listing->isPublished())
		<h1>{{ $listing->name }}</h1>
		Published
	@else
		Unpublished
	@endif

	@foreach ($listingPolymorphicRelationships as $relationship)
		@foreach ($listing->$relationship as $entity)
			{{ $entity->name }} x {{ $entity->pivot->quantity }}<br>
		@endforeach
	@endforeach
@endforeach
