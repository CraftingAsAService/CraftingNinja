		<header class='header header--layout-3' id='header'>

			<!-- Header Top Bar -->
			<div class='header__top-bar clearfix'>
				<div class='container'>
					<div class='header__top-bar-inner'>

						<!-- Social Links -->
						<ul class='social-links social-links--inline social-links--main-nav social-links--top-bar'>
							<li class='social-links__item'>
								<a href='https://reddit.com/r/craftingasaservice' class='social-links__link' data-toggle='tooltip' data-placement='bottom' title='Reddit'><i class='fab fa-fw fa-reddit'></i></a>
							</li>
							<li class='social-links__item'>
								<a href='https://www.twitter.com/tickthokk' class='social-links__link' data-toggle='tooltip' data-placement='bottom' title='Twitter'><i class='fab fa-fw fa-twitter'></i></a>
							</li>
							<li class='social-links__item'>
								<a href='https://www.patreon.com/craftingasaservice' class='social-links__link' data-toggle='tooltip' data-placement='bottom' title='Patreon'><i class='fab fa-fw fa-patreon'></i></a>
							</li>
						</ul>
						<!-- Social Links / End -->

						<!-- Account Navigation -->
						<ul class='nav-account'>
							{{-- <li class='nav-account__item nav-account__item--wishlist'><a href='_esports_shop-wishlist.html'>Wishlist <span class='highlight'>8</span></a></li> --}}
							{{-- <li class='nav-account__item'><a href='#'>Currency: <span class='highlight'>USD</span></a>
								<ul class='main-nav__sub'>
									<li><a href='#'>USD</a></li>
									<li><a href='#'>EUR</a></li>
									<li><a href='#'>GBP</a></li>
								</ul>
							</li> --}}
							<li class='nav-account__item'><a href='#'>Language: <span class='highlight'>{{ strtoupper(app()->getLocale()) }}</span></a>
								<ul class='main-nav__sub'>
									@foreach (config('translatable.locales') as $language => $locale)
									<li><a href='/locale/{{ $locale }}'>{!! $language !!}</a></li>
									@endforeach
								</ul>
							</li>
							@if (auth()->check())
								<li class='nav-account__item'><a href='/account'>Your Account</a></li>
								<li class='nav-account__item nav-account__item--logout'><a href='/logout'>Sign out</a></li>
							@else
								<li class='nav-account__item nav-account__item--logout'><a href='/login'>Sign in</a></li>
							@endif
						</ul>
						<!-- Account Navigation / End -->
					</div>
				</div>
			</div>
			<!-- Header Top Bar / End -->

			<!-- Header Primary -->
			<div class='header__primary'>
				<div class='container'>
					<div class='header__primary-inner'>

						<!-- Header Logo -->
						<div class='header-logo'>
							<a href='/'><img src='/images/logo.png' srcset='/images/logo@2x.png 2x' alt='Alchemists' class='header-logo__img'></a>
							{{-- /images/favicon@2x.png --}}
						</div>
						<!-- Header Logo / End -->

						<!-- Main Navigation -->

						<nav class='main-nav'>
							<ul class='main-nav__list'>
								@if ( ! config('game'))
									<li class='{{ request()->path() == '/' ? ' active' : '' }}'><a href='/#games'>Games</a></li>
								@else
									@php
										$navigation = [
											'Compendium' => '/compendium',
											'Equipment'  => '/equipment',
											'Quests'     => '/quests',
										];
									@endphp
									@foreach ($navigation as $section => $url)
										<li class='{{ isset($active) && $active == $url ? ' active' : '' }}'><a href='{{ $url }}'>{!! $section !!}</a></li>
									@endforeach

								@endif
							</ul>
						</nav>
						<!-- Main Navigation / End -->

						<div class='header__primary-spacer'></div>

						<!-- Header Search Form -->
						@if (config('game'))
						<div class='header-search-form'>
							@if ( ! \Request::is('compendium'))
							<form action='/compendium' method='post' id='mobile-search-form' class='search-form'>
								@csrf
								<input type='text' class='form-control header-mobile__search-control' name='search' value='{{ request('search') }}' placeholder='Search the compendium'>
								<button type='submit' class='header-mobile__search-submit'><i class='fa fa-search'></i></button>
							</form>
							@endif
						</div>
						@endif
						<!-- Header Search Form / End -->

						<!-- Header Info Block -->
						@if (config('game'))
						<ninja-cart></ninja-cart>
						@endif
						<!-- Header Info Block / End -->

					</div>
				</div>
			</div>
			<!-- Header Primary / End -->

		</header>
