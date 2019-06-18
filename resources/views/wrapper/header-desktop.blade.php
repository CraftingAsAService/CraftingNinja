		<header class='header header--layout-3'>

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
								<li class='nav-account__item nav-account__item--logout'><a href='/login/google'>Sign in</a></li>
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
											'Crafting'	 => '/crafting',
											'Compendium' => '/compendium',
											'Equipment'	 => '/equipment',
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
						<ul class='info-block info-block--header ninja-cart'>

							<li :class='"info-block__item info-block__item--shopping-cart" + (count ? " js-info-block__item--onclick" : "")'>
								<a href='/knapsack' class='info-block__link-wrapper'>
									<svg role='img' class='df-icon df-icon--shopping-cart'>
										<use xlink:href='/alchemists/images/esports/icons-esports.svg#cart'/>
									</svg>
									<h6 class='info-block__heading'>Your Bag</h6>
									<span class='info-block__cart-sum'>
										<span v-if='count == 0'>empty</span>
										<span v-else>
											<span v-html='count'></span> item<span v-if='count != 1'>s</span>
										</span>
									</span>
								</a>

								<!-- Dropdown Shopping Cart -->
								<ul class='header-cart header-cart--inventory'>

									<li class='header-cart__item header-cart__item--title'>
										<h5>Inventory</h5>
									</li>

									<li class='header-cart__item'>
										<figure class='header-cart__product-thumb'>
											<img src='/alchemists/images/esports/samples/cart-sm-1.jpg' alt='Jaxxy Framed Art Print'>
										</figure>
										<div class='header-cart__badges'>
											<span class='badge badge-primary'>2</span>
											<span class='badge badge-default badge-close'><i class='fa fa-times'></i></span>
										</div>
									</li>
									<li class='header-cart__item'>
										<figure class='header-cart__product-thumb'>
											<img src='/alchemists/images/esports/samples/cart-sm-4.jpg' alt='Mercenaries Framed Art Print'>
										</figure>
										<div class='header-cart__badges'>
											<span class='badge badge-default badge-close'><i class='fa fa-times'></i></span>
										</div>
									</li>
									{{-- Fill space for style --}}
									<li class='header-cart__item'>
										<figure class='header-cart__product-thumb'></figure>
									</li>

									<li class='header-cart__item header-cart__item--action'>
										<a href='/crafting/list' class='btn btn-primary btn-block'>
											<i class='fas fa-magic'></i>
											Craft
										</a>
									</li>
								</ul>
								<!-- Dropdown Shopping Cart / End -->

							</li>
						</ul>
						@endif
						<!-- Header Info Block / End -->

					</div>
				</div>
			</div>
			<!-- Header Primary / End -->

		</header>
