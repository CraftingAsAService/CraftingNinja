<ul class='products products--grid products--grid-5 products--grid-simple'>
	<li class='product__item -item' v-for='(data, index) in results.data'>
		<div class='product__img'>
			<div class='product__thumb'>
				<img v-bind:src='"/assets/{{ config('game.slug') }}/item/" + data.icon + ".png"' v-bind:alt='"Image of " + data.id'>
				<span class='levels badge badge-pill badge-light'>
					<span class='ilvl' v-html='data.ilvl'></span>
					<span class='rlvl' v-html='data.level'></span>
					<span class='difficulty' v-if='data.sublevel'>
						<span class='sublevel-icon' v-for='n in data.sublevel'></span>
					</span>
				</span>
				<span class='category badge badge-pill badge-secondary' v-if='data.category' v-html='data.category'></span>
			</div>
			<div class='product__overlay'>
				<div class='product__btns'>
					<ninja-bag-button text='Add to bag' icon='icon-bag' :type='chapter' :id='data.id' :img='"/assets/{{ config('game.slug') }}/item/" + data.icon + ".png"'></ninja-bag-button>
				</div>
			</div>
		</div>
		<div class='jobs'>
			<span class='rjobs' v-if='data.job'>
				<span class='few-classes'>
					<img width='24' height='24' v-bind:src='"/assets/{{ config('game.slug') }}/jobs/crafting-" + data.job.icon + ".png"' v-tooltip:bottom='data.job.icon'>
				</span>
			</span>
		</div>
		<div class='product__content card__content'>
			<div class='product__header'>
				<div class='product__header-inner'>
					<h2 v-bind:class='"product__title rarity-" + data.rarity' v-html='data.name'></h2>
				</div>
			</div>
		</div>
	</li>
</ul>
