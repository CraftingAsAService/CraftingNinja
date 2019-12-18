<template>
	<!-- <crafting-recipe recipeId='12345'></crafting-recipe> -->
	<div class='row item'>
		<div class='col-auto'>
			<img :src='"/assets/" + gameSlug + "/i/" + icon + ".png"' alt='' width='48' height='48' class='icon'>
		</div>
		<div class='col info' :style='topTierCrafts[recipeId].need <= 0 ? "opacity: .5;" : ""'>
			<span class='required text-warning' v-if='topTierCrafts[recipeId].need > 0' v-html='topTierCrafts[recipeId].need'></span>
			<small class='text-muted' v-if='topTierCrafts[recipeId].need > 0'>x</small>
			<big :class='"rarity-" + rarity' v-html='itemName'></big>
		</div>
		<div class='col-auto'>
			<div class='form-group tally'>
				<label class='checkbox ml-2' style='width: 24px;'>
					<input type='checkbox' v-model='checked'>
					<span class='checkbox-indicator' style='width: 24px; height: 24px; top: -10px;'></span>
				</label>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: [ 'recipeId', 'itemId', 'itemName', 'topTierCrafts' ],
		data () {
			return {
				gameSlug: game.slug,
				nodeTypes: nodeTypes,
				nodes: nodes,
				sources: {},
				icon: '',
				rarity: '',
				have: 0,
				need: 0,
				required: 0,
				checked: false
			}
		},
		mounted:function() {
			this.icon = items[this.itemId].icon;
			this.rarity = items[this.itemId].rarity;
		},
		created:function() {
			// console.log('created');
			// this.$eventBus.$on('reagentAmountsUpdated', this.amountUpdate);
		},
		beforeDestroy:function() {
			// console.log('beforeDestroy');
			// this.$eventBus.$off('reagentAmountsUpdated');
		},
		watch: {
			checked:function(truthy) {
				this.$emit('pass-have-item-to-parent', this.itemId, truthy);
			}
		},
		methods: {
			// amountUpdate:function(a, b, c, allAmounts) {
			// 	console.log(a, b, c);

			// 	this.have = allAmounts[this.itemId].have;
			// 	this.need = allAmounts[this.itemId].need;
			// 	this.required = allAmounts[this.itemId].required;
			// }
		}
	}
</script>
