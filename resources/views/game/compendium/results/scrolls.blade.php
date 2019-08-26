<ul class='products products--grid products--grid-2 products--grid-simple'>
	<li class='product__item -scroll posts__item card p-3' v-for='(data, index) in results.data'>
		<div class='posts__inner'>
			{{-- <a href='#' class='float-right' @click.prevent='openScroll(index)'><i class='fas fa-book'></i></a> --}}
			{{-- <div class='posts__item--card'> --}}
			{{-- </div> --}}
			<a href='#' class='float-right' @click.prevent='openScroll(index)'><i class='fas fa-cart-plus'></i></a>

			<h4 class='posts__title posts__title--color-hover mb-1'>
				<a href='#' v-html='data.name' @click.prevent='openScroll(index)'></a>
			</h4>
			<p class='description mt-1 mb-2 truncate-text' v-html='data.description'></p>
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
						<span class='label posts__cat-label -job posts__cat-label--category-2' v-if='data.job.icon' v-html='data.job.icon'></span>
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
			{{-- <div class='mt-3' v-if='expanded == index'>
				<div v-for='(entities, key) in data.entities'>
					<div v-for='(entity, index) in entities'>
						<span v-html='entity.name'></span>
						<span v-html='entity.quantity'></span>
					</div>
				</div>
			</div>
			<div class='mt-3' v-if='expanded == index'>
				<div class='row'>
					<div class='col'>
						<ninja-bag-button text='Add to bag' icon='icon-bag' :type='chapter' :id='data.id' :img='"/assets/{{ config('game.slug') }}/" + chapter + "/" + data.icon + ".png"'></ninja-bag-button>
					</div>
					<div class='col-auto'>
						<button type='button' class='btn btn-primary -heart'><i class='far fa-heart' v-if=' ! data.my_vote'></i> <i class='fas fa-heart' v-if='data.my_vote'></i></button>
					</div>
				</div>
			</div> --}}
		</div>
	</li>
</ul>
