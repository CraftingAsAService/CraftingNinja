<div class='wrapper'>
	<section class='header list-group'>
		<div class='list-group-item'>
			<i class="fa fa-close pull-xs-right close-menu"></i>
			Languages Menu
		</div>
	</section>

	<section class='options list-group'>
		@foreach (config('languages') as $lang => $language)
		<a href="/language/{{ $lang }}" class="list-group-item">
			<i class="fa fa-{{ $lang == app()->getLocale() ? 'check-' : '' }}square-o pull-xs-right"></i>
			{{$language}}
		</a>
        @endforeach
	</section>
</div>