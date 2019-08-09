<ul class='products products--grid products--grid-5 products--grid-simple'>
	<li class='product__item -item' v-for='(data, index) in results.data'>
		<div class='product__img'>
			<div class='product__thumb'>
				<img v-bind:src='"/assets/{{ config('game.slug') }}/item/" + data.icon + ".png"' v-bind:alt='"Image of " + data.id'>
				<span class='levels badge badge-pill badge-light'>
					<span class='ilvl' v-html='data.ilvl'></span>
					<span class='rlvl' v-if='data.recipes && data.ilvl != data.recipes[0].level' v-html='data.recipes[0].level'></span>
					<span class='elvl' v-if='data.equipment && data.ilvl != data.equipment.level' v-html='data.equipment.level'></span>
					<span class='difficulty' v-if='data.recipes && data.recipes[0].sublevel'>
						<span class='sublevel-icon' v-for='n in data.recipes[0].sublevel'></span>
					</span>
				</span>
				<span class='category badge badge-pill badge-secondary' v-if='data.category' v-html='data.category'></span>
			</div>
			<div class='product__overlay'>
				<div class='product__btns'>
					<ninja-bag-button text='Add to bag' icon='icon-bag' :type='["recipe","item","equipment"].includes(chapter) ? "item" : chapter' :id='data.id' :img='"/assets/{{ config('game.slug') }}/item/" + data.icon + ".png"'></ninja-bag-button>
				</div>
			</div>
		</div>
		<div class='jobs'>
			<span class='rjobs'>
				<span class='many-classes' v-if='data.recipes && data.recipes.length > 2' v-tooltip:bottom='data.recipes.length + " Classes can Craft"'>
					<i class='fas fa-user-ninja'></i>
					<i class='fas fa-plus'></i>
				</span>
				<span class='few-classes' v-else>
					<img width='24' height='24' v-for='(recipe, rindex) in data.recipes' v-bind:src='"/assets/{{ config('game.slug') }}/jobs/crafting-" + recipe.job.icon + ".png"' v-tooltip:bottom='recipe.job.icon'>
				</span>
			</span>
			<span class='ejobs' v-if='data.equipment'>
				<span class='many-classes' v-if='data.equipment && data.equipment.jobs.length > 2' v-tooltip:bottom='data.equipment.jobs.length + " Classes can Equip"'>
					<i class='fas fa-user-ninja'></i>
					<i class='fas fa-plus'></i>
				</span>
				<span class='few-classes' v-else>
					<img width='24' height='24' v-for='(job, jindex) in data.equipment.jobs' v-bind:src='"/assets/{{ config('game.slug') }}/jobs/" + job.icon + ".png"' v-tooltip:bottom='job.icon'>
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
