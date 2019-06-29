<template>
	<ul class='info-block info-block--header ninja-cart'>
		<li :class='"info-block__item info-block__item--shopping-cart" + (count ? " js-info-block__item--onclick" : "")'>
			<a href='/knapsack' class='info-block__link-wrapper'>
				<svg ref='bagIcon' role='img' class='df-icon df-icon--shopping-cart'>
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
					<small class='float-right' v-if='contents.length > 9'>Recent entries</small>
					<h5>Knapsack</h5>
				</li>
				<li class='header-cart__item' v-for='(entry, index) in reverseContents' v-if='index < 9'>
					<figure class='header-cart__product-thumb'>
						<img :src='entry.p' alt=''>
					</figure>
					<div class='header-cart__badges'>
						<span class='badge badge-primary' v-if='entry.q > 1' v-html='entry.q'></span>
						<span class='badge badge-default badge-close' @click='removeFromCart(index, "index")'><i class='fa fa-times -desize'></i></span>
					</div>
				</li>
				<li class='header-cart__item header-cart__item--action'>
					<a href='/knapsack' class='btn btn-primary-inverse btn-block'>
						<i class='fas fa-th-list'></i>
						Manage Knapsack
					</a>
					<a href='/crafting/knapsack' class='btn btn-primary btn-block'>
						<i class='fas fa-magic'></i>
						Craft
					</a>
				</li>
			</ul>
			<!-- Dropdown Shopping Cart / End -->
		</li>
	</ul>
</template>

<script>
	export default {
		data () {
			return {
				contents: [],
				count: 0
			}
		},
		created:function() {
			this.$eventBus.$on('addToCart', this.addToCart);
			this.$eventBus.$on('removeFromCart', this.removeFromCart);
		},
		beforeDestroy:function() {
			this.$eventBus.$off('addToCart');
			this.$eventBus.$off('removeFromCart');
		},
		mounted:function() {
			this.loadFromCookie();
		},
		computed: {
			reverseContents() {
				return this.contents.slice().reverse();
			}
		},
		methods: {
			addToCart:function(id, type, quantity, img, el) {
				this.addToCartAnimation(img, el);
				this.addToCookie(id, type, quantity, img);
			},
			removeFromCart:function(id, type) {
				if (type == 'index')
					this.removeFromCartByIndex(id);
				else
					this.removeFromCartByType(id, type);

				this.saveToCookie();
				this.recount();
			},
			removeFromCartByIndex:function(index) {
				this.contents.splice(index, 1);
			},
			removeFromCartByType:function(id, type) {
				for (var index in this.contents) {
					if (this.contents[index].t == type && this.contents[index].i == id) {
						this.removeFromCartByIndex(index);
						break;
					}
				}
			},
			addToCookie:function(id, type, quantity, img) {
				// Look for an existing entry
				var hasExistingEntry = false;
				for (var index in this.contents)
				{
					if (this.contents[index].t == type && this.contents[index].i == id)
					{
						this.contents[index].q += quantity;
						hasExistingEntry = true;
						break;
					}
				}

				if ( ! hasExistingEntry)
					this.contents.push({
						"i": id,
						"t": type,
						"q": quantity,
						"p": img
					});

				this.saveToCookie();
			},
			addToCartAnimation:function(img, el) {
				var fromPosition = $(el).offset(),
					bagIconEl = $(this.$refs.bagIcon),
					toPosition = bagIconEl.offset(),
					imgEl = $('<img src="' + img + '">');

				toPosition.top += bagIconEl.outerHeight() / 2;
				toPosition.left += bagIconEl.outerWidth() / 2;

				var thisObj = this;

				imgEl
					.css({
						// 'opacity': '.5',
						'position': 'absolute',
						'top': fromPosition.top + 'px',
						'left': fromPosition.left + 'px',
						'z-index': '500',
						'width': '48px',
						'height': '48px',
						'border': '1px solid var(--dark)',
						'background-color': 'var(--dark)',
						'border-radius': '50%'
					})
					.appendTo($('body'))
					.animate({
						'top': toPosition.top + 'px',
						'left': toPosition.left + 'px',
					}, {
						easing: 'linear',
						duration: 400
					});

				setTimeout(function() {
					bagIconEl
						.css({ 'position': 'absolute' })
						.animate({ left: '-3px' }, 50)
						.animate({ left: '7px' }, 50)
						.animate({ left: '-7px' }, 50)
						.animate({ left: '3px' }, 50)
						.animate({ left: '0px' }, {
							duration: 50,
							always:function() {
								bagIconEl.css({
									'position': '',
									'left': ''
								});

								// not recounting until bag shakes
								thisObj.recount();
							}
						});
				}, 500); // Somewhere between the number above (400), and that number plus the number below (400+250)

				imgEl.animate({
					'width': 0,
					'height': 0
				}, 200, function() {
					imgEl.remove();
				});
			},
			loadFromCookie:function() {
				this.parse();
				this.recount();
			},
			parse:function() {
				let cookieValue = this.$cookies.get('NinjaCart');

				if (cookieValue === null)
					return {};

				this.contents = JSON.parse(cookieValue);
			},
			saveToCookie:function() {
				this.$cookies.set('NinjaCart', JSON.stringify(this.contents));
			},
			recount:function() {
				let count = 0;

				for (var entry in this.contents)
					count += this.contents[entry].q;

				this.count = count;
			}
		}
	}
</script>
