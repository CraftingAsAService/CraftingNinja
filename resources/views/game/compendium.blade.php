@extends('app', [
	'active' => '/compendium',
	'js' => [
		'pages/compendium'
	]
])

@section('topContent')
	<div class='minor-media mb-3' hidden>
		<img src='/assets/{{ config('game.slug') }}/cover.jpg' alt='{{ config('game.data.name') }}'>
		<div class='text'>
			<h1>Compendium</h1>
		</div>
	</div>
@endsection

@section('content')


			<div class='row'>

				<!-- Products -->
				<div class='col-md-9 order-md-2'>

					<!-- Shop Grid -->
					<div class='card card--clean'>
						<header class='card__header card__header--shop-filter'>

							<div class='shop-filter'>
								<h5 class='shop-filter__result'>Showing 12 of 98 <span>Items</span></h5>
								<ul class='shop-filter__params'>
									<li class='shop-filter__control'>
										<select class='form-control input-xs'>
											<option>Name: A-Z</option>
											<option>Name: Z-A</option>
											<option>iLv: Low to High</option>
											<option>iLv: High to Low</option>
										</select>
									</li>
									<li class='shop-filter__control'>
										<select class='form-control input-xs'>
											<option>Show 12 per page</option>
											<option>Show 24 per page</option>
											<option>Show 36 per page</option>
										</select>
									</li>
								</ul>
							</div>

						</header>
						<div class='card__content'>

							<div id='pre-results' class='jumbotron'>
								<h1 class='display-4'>What are you looking for?</h1>
								<p class='lead mt-4 mb-0'>Select a <i class='fas fa-bookmark'></i> chapter and start <i class='fas fa-filter'></i> filtering!</p>
							</div>

							<div id='no-results' class='jumbotron' hidden>
								<h1 class='display-4'>No results!</h1>
								<p class='lead mt-4 mb-0'>Tweak those <i class='fas fa-filter'></i> filters!</p>
							</div>

							<!-- Products -->
							<ul class='products products--grid products--grid-3 products--grid-simple' hidden>

								<!-- Product #0 -->
								<li class='product__item'>

									<div class='product__img'>
										<div class='product__thumb'>
											<img src='assets/images/esports/samples/product-1.jpg' alt=''>
										</div>
										<div class='product__overlay'>
											<div class='product__btns'>
												<a href='_esports_shop-cart.html' class='btn btn-primary-inverse btn-block btn-icon'><i class='icon-bag'></i> Add to your bag</a>
												<a href='_esports_shop-wishlist.html' class='btn btn-primary btn-block btn-icon'><i class='icon-heart'></i> Add to wishlist</a>
											</div>
										</div>
									</div>

									<div class='product__content card__content'>
										<div class='product__header'>
											<div class='product__header-inner'>
												<h2 class='product__title'><a href='_esports_shop-product.html'>Jaxxy Framed Art Print</a></h2>
												<div class='product__ratings'>
													<i class='fa fa-star'></i>
													<i class='fa fa-star'></i>
													<i class='fa fa-star'></i>
													<i class='fa fa-star'></i>
													<i class='fa fa-star'></i>
												</div>
												<div class='product__price'>
														$48.00
												</div>
											</div>
										</div>
									</div>
								</li>
								<!-- Product #0 / End -->

							</ul>
							<!-- Products / End -->
						</div>
					</div>
					<!-- Shop Grid / End -->

					<!-- Shop Pagination -->
					<nav class='shop-pagination' aria-label='Shop navigation' hidden>
						<ul class='pagination pagination--circle justify-content-center'>
							<li class='page-item'><a class='page-link' href='#'><i class='fa fa-angle-left'></i></a></li>
							<li class='page-item active'><a class='page-link' href='#'>1</a></li>
							<li class='page-item'><a class='page-link' href='#'><i class='fa fa-angle-right'></i></a></li>
						</ul>
					</nav>
					<!-- Shop Pagination / End -->

				</div>
				<!-- Products / End -->

				@include('game.compendium.sidebar')
			</div>

	<div id='results'>
		<div class='compendium-item -template media' hidden>
			<img src='' alt='' width='48' height='48'>
			<div class='media-body'>
				<h6 class='name'></h6>
				<div class='secondary'>
					<span class='ilvl'></span>
					<span class='recipes'>
						<span class='job'><img src='' alt='' width='24' height='24'><img src='' alt='' width='24' height='24'><span class='multiple'>✚</span></span>
						<span class='level'></span>
					</span>
					<span class='equipment'>
						<span class='job'><img src='' alt='' width='24' height='24'><img src='' alt='' width='24' height='24'><span class='multiple'>✚</span></span>
						<span class='level'></span>
					</span>

					<div class='category'></div>
				</div>
			</div>
			<div class='button'>
				<button type='button' class='btn btn-light add-to-list' data-id='' data-type='item'>
					<img src='/images/icons/swap-bag.svg' class='svg-icon' alt=''>
					<span class='badge badge-light' hidden></span>
				</button>
			</div>
		</div>
	</div>


@endsection
