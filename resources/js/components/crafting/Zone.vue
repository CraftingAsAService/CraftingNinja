<template>
	<div class='zone' v-show='shown'>
		<h5 class='name'>
			<i class='fas fa-map-marked -desize float-right' hidden></i>
			<span v-html='zone.name'></span>
		</h5>
		<crafting-item v-for='itemId in itemIds' :key='itemId' :item-id='itemId' :zone-id='zoneId'></crafting-item>
		<hr>
	</div>
</template>

<script>
	Vue.component('crafting-item', require('../components/crafting/Item.vue').default);

	export default {
		props: [ 'zoneId' ],
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
