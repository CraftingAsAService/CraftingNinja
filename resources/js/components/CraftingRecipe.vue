<template>
	<div class='row item' v-show='shown'>
		<div class='col-auto'>
			<img :src='"/assets/" + game.slug + "/i/" + item.icon + ".png"' alt='' width='48' height='48' class='icon'>
		</div>
		<div class='col info' :style='recipe.need <= 0 ? "opacity: .5;" : ""'>
			<span class='required text-warning' v-if='recipe.need > 0' v-html='recipe.need'></span>
			<small class='text-muted' v-if='recipe.need > 0'>x</small>
			<big :class='"rarity-" + item.rarity' v-html='item.name'></big>
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

	export default {
		props: [ 'recipeId', 'jobId' ],
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
					return actions.fcfsRecipeJobTierPreference(this.recipeId, this.jobId, this.tierId);
				}
			},
			recipe() {
				return {
					...this.recipeData[this.recipeId],  // "Official" recipe data, level/yield/etc
					...getters.recipes()[this.recipeId] // "Crafting" recipe data, have/need/required
				};
			},
			item() {
				return this.itemData[this.recipe.item_id];
			},
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
