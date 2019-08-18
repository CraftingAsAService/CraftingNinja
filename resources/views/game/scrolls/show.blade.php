{{-- TODO LAYOUT --}}

<h1>{{ $scroll->name }}</h1>

@foreach ($scrollPolymorphicRelationships as $relationship)
	@foreach ($scroll->$relationship as $entity)
		{{ $entity->name }} x {{ $entity->pivot->quantity }}<br>
	@endforeach
@endforeach
