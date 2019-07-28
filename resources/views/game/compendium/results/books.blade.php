<ul class='products products--grid products--grid-2 products--grid-simple'>
	<li class='product__item posts__item card p-3' v-for='(data, index) in results.data'>
		<div class='posts__inner'>
			<div class='posts__cat'>
				<span class='label posts__cat-label posts__cat-label--category-2' v-if='data.job.icon' v-html='data.job.icon'></span>
				<span class='label posts__cat-label posts__cat-label--category-5' v-if='data.min_level || data.max_level'>
					Lv
					<span v-if='data.min_level > 0' v-html='data.min_level'></span>
					<span v-if='data.min_level > 0 && data.max_level > 0'>&rarr;</span>
					<span v-if='data.max_level > 0' v-html='data.max_level'></span>
				</span>
			</div>
			<h4 class='posts__title posts__title--color-hover mb-1'>
				<a href='#' v-html='data.name'></a>
			</h4>
			<p class='truncate-text mb-1' v-html='data.description'></p>
			<p class='m-0'>
				Authored
				<time :datetime='data.updated_at' v-html='data.last_updated'></time>
				by
				<span v-html='data.user.name'></span>
			</p>
		</div>
	</li>
</ul>
