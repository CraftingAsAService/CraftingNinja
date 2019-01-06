{{-- TODO LAYOUT --}}

@foreach ($listings as $listing)

	@if ($listing->isPublished())
		<h1>{{ $listing->name }}</h1>
		Published
	@else
		Unpublished
	@endif

	@foreach ($listing->items as $item)
		{{ $item->name }} x {{ $item->pivot->quantity }}<br>
	@endforeach

	@foreach ($listing->recipes as $recipe)
		{{ $recipe->product->name }} x {{ $recipe->pivot->quantity }}<br>
	@endforeach

	@foreach ($listing->nodes as $node)
		{{ $node->name }} x {{ $node->pivot->quantity }}<br>
	@endforeach

	@foreach ($listing->objectives as $objective)
		{{ $objective->name }} x {{ $objective->pivot->quantity }}<br>
	@endforeach

@endforeach
