{{-- TODO LAYOUT --}}

<h1>{{ $book->title }}</h1>

@foreach ($book->items as $item)
	{{ $item->name }} x {{ $item->pivot->quantity }}<br>
@endforeach
