<template>
	<div class='job' v-show='shown'>
		<h5 class='name'>
			<i class='fas fa-map-marked -desize float-right' hidden></i>
			<span v-html='job.name'></span>
		</h5>
		<crafting-recipe v-for='itemId in itemIds' :key='itemId' :item-id='itemId' :job-id='jobId'></crafting-recipe>
		<hr>
	</div>
</template>

<script>
	Vue.component('crafting-recipe', require('../components/CraftingRecipe.vue').default);

	export default {
		props: [ 'jobId' ],
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
			zone() {
				return this.zoneData[this.zoneId];
			},
			itemIds() {
				return Object.keys(this.breakdown[this.zoneId]);
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
