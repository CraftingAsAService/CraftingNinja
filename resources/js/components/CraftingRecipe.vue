<template>
	<div class='row recipe' v-show='shown'>
		<div class='col-auto'>
			<img :src='"/assets/" + game.slug + "/i/" + item.icon + ".png"' alt='' width='48' height='48' class='icon'>
		</div>
		<div class='col info' :style='recipe.need <= 0 ? "opacity: .5;" : ""'>
			<span class='required text-warning' v-if='recipe.need > 0' v-html='recipe.need'></span>
			<small class='text-muted' v-if='recipe.need > 0'>x</small>
			<big :class='"rarity-" + item.rarity' v-html='item.name'></big>

			<div class='sources'>
				<crafting-recipe-source v-for='sourceJobId in sources' :key='recipe.id + sourceJobId + tierId' :section-job-id='recipe.job_id' :job-id='sourceJobId' :tier-id='tierId' :recipe-id='recipe.id'></crafting-recipe-source>
				<template v-for='(sourceTypes, sourceZoneId) in itemSources'>
					<template v-for='(sourceData, type) in sourceTypes'>
						<crafting-reagent-source v-for='(info, id) in sourceData' :key='sourceZoneId + type + id' :section-zone-id='zoneId' :zone-id='sourceZoneId' :item-id='item.id' :type='type' :id='id' :info='info'></crafting-reagent-source>
					</template>
				</template>
			</div>
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
	import { getters, mutations, actions } from '../stores/crafting';

	Vue.component('crafting-recipe-source', require('../components/CraftingRecipeSource.vue').default);
	Vue.component('crafting-reagent-source', require('../components/CraftingReagentSource.vue').default);

	export default {
		props: [ 'recipeId', 'tierId' ],
		data () {
			return {
				// progress: 0,
				checked: false
			}
		},
		// created:function() {
		// 	this.$eventBus.$on('recipe' + this.recipe.id + 'data', this.amountUpdate);
		// },
		// mounted:function() {
		// },
		// beforeDestroy:function() {
		// 	this.$eventBus.$off('recipe' + this.recipe.id + 'data');
		// },
		computed: {
			shown: {
				cache: false,
				get() {
					return actions.fcfsItemJobTierPreference(this.item.id, this.recipe.job_id, this.tierId);
				}
			},
			sources() {
				var sources = [];
				Object.entries(this.recipeData).forEach(([recipeId, recipe]) => {
					if (recipe.item_id != this.item.id)
						return;

					if (recipe.job_id == this.recipe.job_id)
						sources.unshift(recipe.job_id);
					else
						sources.push(recipe.job_id);
				});
				return sources;
			},
			itemSources() {
				var itemSources = {};
				Object.keys(this.breakdown).forEach(loopedZoneId => {
					Object.keys(this.breakdown[loopedZoneId]).forEach(loopedItemId => {
						if (loopedItemId == this.item.id)
							itemSources[loopedZoneId] = this.breakdown[loopedZoneId][this.item.id];
					});
				});
				return itemSources;
			},
			recipe() {
				return {
					...this.recipeData[this.recipeId],  // "Official" recipe data, level/yield/etc
					...getters.recipes()[this.recipeId] // "Crafting" recipe data, have/need/required
				};
			},
			item() {
				return this.itemData[this.recipe.item_id];
			}
		},
		watch: {
			checked:function(truthy) {
				console.log('checked!');
				// this.$emit('pass-have-recipe-to-parent', this.recipe.id, truthy);
			}
		},
		methods: {

			// amountUpdate:function(need, have, required) {
			// 	this.need = need;
			// 	this.have = have;
			// 	this.required = required;
			// }
		}
	}
</script>
