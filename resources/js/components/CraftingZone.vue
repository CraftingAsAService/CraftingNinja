<template>
	<div class='zone'>
		<h5 class='name'>
			<i class='fas fa-map-marked -desize float-right' hidden></i>
			<span v-html='zone.name'></span>
		</h5>
		<crafting-reagent v-for='itemId in itemIds' :key='itemId' :item-id='itemId' :zone-id='zoneId'></crafting-reagent>
		<hr>
	</div>
</template>

<script>
	Vue.component('crafting-reagent', require('../components/CraftingReagent.vue').default);

	export default {
		props: [ 'zoneId' ],
		computed: {
			zone() {
				return this.zoneData[this.zoneId];
			},
			itemIds() {
				return Object.keys(this.breakdown[this.zoneId]);
			}
		}
	}
</script>
