{{-- TODO LAYOUT --}}

<h1>{{ $listing->name }}</h1>

@foreach ($listingPolymorphicRelationships as $relationship)
	@foreach ($listing->$relationship as $entity)
		{{ $entity->name }} x {{ $entity->pivot->quantity }}<br>
	@endforeach
@endforeach
