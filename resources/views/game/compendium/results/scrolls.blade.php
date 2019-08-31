<ul class='products products--grid products--grid-2 products--grid-simple'>
	<li :class='"product__item -scroll" + (expanded == index ? " -expanded" : "") + " posts__item card p-3"' v-for='(data, index) in results.data'>
		<div class='posts__inner'>
			{{-- <a href='#' class='float-right' @click.prevent='toggleScroll(index)'><i class='fas fa-book'></i></a> --}}
			{{-- <div class='posts__item--card'> --}}
			{{-- </div> --}}
			{{-- <a href='#' class='float-right'><i class='fas fa-cart-plus'></i></a> --}}
			<div class='float-right'>
				<ninja-bag-button icon='cart-plus' :list='data.entities'></ninja-bag-button>
			</div>

			<h4 class='posts__title posts__title--color-hover mb-1'>
				<a href='#' v-html='data.name' @click.prevent='toggleScroll(index)'></a>
			</h4>
			<p :class='"description mt-1 mb-2" + (expanded == index ? " truncate-text" : "")' v-html='data.description'></p>
			<div class='row'>
				<div class='col'>
					<p class='author-block m-0'>
						<span class='text-muted'>by</span>
						<span v-html='data.author.name'></span>,
						<time :datetime='data.updated_at' v-html='data.last_updated'></time>
					</p>
				</div>
				<div class='col-auto'>
					<div class='posts__cat p-0 m-0'>
						<span class='label posts__cat-label -job posts__cat-label--category-2' v-if='data.job && data.job.icon' v-html='data.job.icon'></span>
						<span class='label posts__cat-label -level posts__cat-label--category-5' v-if='data.min_level || data.max_level'>
							Lv
							<span v-if='data.min_level > 0' v-html='data.min_level'></span>
							<span v-if='data.min_level > 0 && data.max_level > 0'>&rarr;</span>
							<span v-if='data.max_level > 0' v-html='data.max_level'></span>
						</span>
						<span class='label posts__cat-label -heart posts__cat-label--category-4'>
							<i class='far fa-heart' v-if=' ! data.my_vote'></i> <i class='fas fa-heart' v-if='data.my_vote'></i> <span v-html='data.votes'></span>
						</span>
					</div>
				</div>
			</div>
			<div class='mt-3' v-if='expanded == index'>
				<ul class='alc-inventory__list list-unstyled' v-for='(entities, key) in data.entities'>
					<li class='alc-inventory__item -small' v-for='(entity, index) in entities'>
						<figure class='alc-inventory__item-thumb'>
							<img :src='"/assets/{{ config('game.slug') }}/item/" + entity.icon + ".png"' :alt='entity.name'>
						</figure>
						<div class='alc-inventory__item-badges' v-if='entity.pivot.quantity > 1'>
							<span class='badge badge-primary' role='info' v-html='entity.pivot.quantity'></span>
						</div>
					</li>
				</ul>
			</div>
			<div class='mt-3' v-if='expanded == index'>
				<div class='row'>
					<div class='col'>

					</div>
					<div class='col'>
						<ninja-bag-button text='Add to bag' icon='icon-bag' :list='data.entities'></ninja-bag-button>
					</div>
					<div class='col-auto'>
						<button type='button' class='btn btn-primary -heart'><i class='far fa-heart' v-if=' ! data.my_vote'></i> <i class='fas fa-heart' v-if='data.my_vote'></i></button>
					</div>
				</div>
			</div>
		</div>
	</li>
</ul>
