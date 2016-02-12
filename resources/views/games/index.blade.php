@extends('app')

@section('banner')
    <h1>Select your Game</h1>
@endsection

@section('content')

    @foreach($games as $game)
    <p>
        <a href='/games/{{ $game->slug }}{{ $selection ? ('?selection=' . $selection) : '' }}'>{{ $game->slug }}</a>
    </p>
    @endforeach

@endsection
