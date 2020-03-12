<template>
	<div class='row recipe mb-1' v-show='shown' :style='"opacity: " + (checked ? ".5" : "1")'>
		<div class='col-auto'>
			<img :src='"/assets/" + game.slug + "/i/" + item.icon + ".png"' alt='' :width='checked ? 24 : 48' :height='checked ? 24 : 48' class='icon'>
		</div>
		<div class='col info' :style='need <= 0 ? "opacity: .5;" : ""'>
			<span v-if='need > 0'>
				<span class='required text-warning' :style='"cursor: pointer; opacity: " + (recipe.have/need/2+.5)' contenteditable v-text='recipe.have' @focus='haveFocus' @blur='haveBlur' @keydown.enter='haveEnter'></span><span class='text-muted'>/</span><span class='required text-warning' v-html='need'></span>
			</span>
			<small class='text-muted' v-if='need > 0'>x</small>
			<big :class='"rarity-" + item.rarity' v-html='item.name'></big>

			<div class='sources' v-if=' ! checked' style='height: 20px; overflow: hidden;'>
				<crafting-source v-for='sourceJobId in sources' :key='recipe.id + sourceJobId + tierId' type='recipe' :section-parent-id='recipe.job_id' :parent-id='sourceJobId' :info='{ tierId: tierId }' :item-id='item.id'></crafting-source>
				<!-- <template v-for='(sourceTypes, sourceZoneId) in itemSources'>
					<template v-for='(sourceData, type) in sourceTypes'>
						<crafting-source v-for='(info, id) in sourceData' :key='sourceZoneId + type + id' :section-zone-id='zoneId' :zone-id='sourceZoneId' :item-id='item.id' :type='type' :id='id' :info='info'></crafting-source>
					</template>
				</template> -->
			</div>
		</div>
		<div class='col-auto' style='display: flex; align-items: center;'>
			<div class='form-group tally' style='margin-bottom: 0;'>
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

	Vue.component('crafting-source', require('../components/crafting/Source.vue').default);

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
			...getters,
			shown: {
				cache: false,
				get() {
					return actions.fcfsItemJobTierPreference(this.item.id, this.recipe.job_id, this.tierId);
				}
			},
			recipe() {
				return {
					...this.recipeData[this.recipeId],  // "Official" recipe data, level/yield/etc
					...this.recipes[this.recipeId] // "Crafting" recipe data, have/required
				};
			},
			item() {
				return this.itemData[this.recipe.item_id];
			},
			need() {
				return Math.max(0, this.recipe.required - this.recipe.have);
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
			}
		},
		watch: {
			checked:function(truthy) {
				if (this.stopCheckedWatcher)
					return;
				this.recipe.have = truthy ? this.need : 0;
				this.updateHaveAmount();
			}
		},
		methods: {
			...mutations,
			haveFocus(event) {
				var range, selection;
				if (document.body.createTextRange) {
					range = document.body.createTextRange();
					range.moveToElementText(event.target);
					range.select();
				} else if (window.getSelection) {
					selection = window.getSelection();
					range = document.createRange();
					range.selectNodeContents(event.target);
					selection.removeAllRanges();
					selection.addRange(range);
				}
			},
			haveEnter(event) {
				event.target.blur();
			},
			haveBlur(event) {
				// Make sure it's a number between 0 and `this.need`
				var inputValue = Math.max(Math.min(parseInt(event.target.innerText.replace(/\D/, '')), this.need), 0);
				// Value might be bad, reset to 0
				if (isNaN(inputValue))
					inputValue = 0;
				// Repopulate content with fixed value
				event.target.innerText = inputValue;

				// Check/Uncheck the box if applicable
				if (inputValue < this.need && this.checked == true)
					this.gentlyUpdateChecked(false);
				else if (inputValue == this.need && this.checked == false)
					this.gentlyUpdateChecked(true);

				this.recipe.have = inputValue;
				this.updateHaveAmount();
			},
			updateHaveAmount(have) {
				this.setRecipeHaveAmount(this.recipeId, this.recipe.have);
				this.triggerRefresh();
			},
			gentlyUpdateChecked(truthy) {
				this.stopCheckedWatcher = true;
				this.checked = truthy;
				Vue.nextTick(() => {
					this.stopCheckedWatcher = false;
				});
			}
		}
	}
</script>
