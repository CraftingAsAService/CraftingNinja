{{-- TODO LAYOUT --}}

<h1>{{ $book->name }}</h1>

@foreach ($book->jottings as $entry)
	{{ $entry->jottable->name }} x {{ $entry->quantity }}<br>
@endforeach
