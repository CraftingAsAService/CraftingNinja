<div class='wrapper'>
	<section class='header list-group'>
		<div class='list-group-item'>
			<i class="fa fa-close pull-xs-right close-menu"></i>
			Account Menu
		</div>
	</section>

	<section class='options list-group'>
		@if (Auth::check())
		<a href="/account" class="list-group-item">
			<i class="fa fa-user pull-xs-right"></i>
			Manage Account
		</a>
		<a href="/logout" class="list-group-item">
			<i class="fa fa-sign-out pull-xs-right"></i>
			Sign-out
		</a>
		@else
		<a href="/login" class="list-group-item">
			<i class="fa fa-sign-in pull-xs-right"></i>
			Login
		</a>
		<a href="/register" class="list-group-item">
			<i class="fa fa-user-plus pull-xs-right"></i>
			Register
		</a>
		@endif
		<a href="#" class="list-group-item open-menu" data-target="#language-menu">
			<i class="fa fa-language pull-xs-right"></i>
			Language Select
		</a>
	</section>
</div>