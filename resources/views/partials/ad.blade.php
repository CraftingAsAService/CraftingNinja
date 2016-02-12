@if ( ! (Auth::check() && Auth::user()->advanced))
	<div class='ad {{ $class or '' }}'>
		<img src='http://placehold.it/728x90' alt='FAUX AD' class='img-fluid img-rounded center-block'>
	</div>
@endif