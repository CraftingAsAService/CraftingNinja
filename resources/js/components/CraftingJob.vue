<template>
	<div class='job' v-show='shown'>
		<h5 class='name'>
			<img :src='src' alt='' width='20' height='20' class='icon mr-1'>
			<span v-html='job.name'></span>
		</h5>
		<crafting-recipe v-for='recipeId in recipeIds' :key='recipeId' :recipe-id='recipeId' :tier-id='tierId'></crafting-recipe>
		<hr>
	</div>
</template>

<script>
	Vue.component('crafting-recipe', require('../components/CraftingRecipe.vue').default);

	export default {
		props: [ 'tierId', 'jobId', 'recipeIds' ],
		// data() {
		// 	return {
		// 		// shown: false,
		// 		itemCount: []
		// 	}
		// },
		// created() {
		// 	this.$eventBus.$on('zoneRefresh', this.zoneRefresh);
		// },
		// beforeDestroy:function() {
		// 	this.$eventBus.$off('zoneRefresh');
		// },
		mounted() {
			this.$forceUpdate();
		},
		computed: {
			shown: {
				cache: false,
				get() {
					for (let c of this.$children)
						if (c.shown)
							return true;
					return false;
				}
			},
			job() {
				return this.jobData[this.jobId];
			},
			src() {
				return '/assets/' + game.slug + '/jobs/' + this.job.icon + '.png';
			},
		},
		// methods: {
		// 	zoneRefresh(zoneId) {
		// 		if (zoneId == this.zoneId)
		// 			this.$forceUpdate();
		// 	}
		// }
	}
</script>
