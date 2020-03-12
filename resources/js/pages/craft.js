/**
 * Craft Page
 */

'use strict';

import { getters, mutations, actions } from '../stores/crafting';
import { clonedeep } from 'lodash';

// "Global" variables, applied to every vue instance
Vue.mixin({
	data() {
		return {
			game: game,
			itemData: items,
			recipeData: recipes,
			jobData: recipeJobs,
			recipeOrder: recipeOrder,
			zoneData: zones,
			maps: maps,
			mobData: mobs,
			nodeData: nodes,
			nodeTypes: nodeTypes,
			breakdown: breakdown,
		}
	},
	created() {
		this.$eventBus.$on('craftRefresh', this.refresh);
	},
	beforeDestroy() {
		this.$eventBus.$off('craftRefresh');
	},
	methods: {
		triggerRefresh() {
			this.$eventBus.$emit('craftRefresh');
		},
		refresh() {
			// console.log('Refreshing component', this);
			this.$forceUpdate();
		}
	}
});

Vue.component('crafting-zone', require('../components/crafting/Zone.vue').default);
Vue.component('crafting-map', require('../components/crafting/Map.vue').default);
Vue.component('crafting-job', require('../components/crafting/Job.vue').default);

const craft = new Vue({
	name: 'Crafting',
	el: '#craft',
	data() {
		return {
			activeMap: 0,
			sortZonesBy: 'efficiency', // 'alphabetical',
		}
	},
	created() {
		this.registerItemsAndRecipes();
		this.calculateAll();

		this.$eventBus.$on('craftRefresh', this.calculateAll);
	},
	computed: {
		...getters,
		sortedZones() {
			// Because this needs to be reactive, it's a `method`, and not a `computed`
			// Get a new copy of breakdown
			let sortedZones = [],
				blankZones = [],
				sortableBreakdown = _.cloneDeep(this.breakdown);

			// Most likely they were hovering a tooltip; hide it - its moving
			// $('.tooltip').tooltip('hide');

			while (Object.keys(sortableBreakdown).length > 0) {
				// Sort it in reverse by the number of items it has
				let sorted = this.sortZonesBy == 'efficiency'
					? Object.entries(sortableBreakdown).sort((a, b) => {
						if (Object.keys(a[1]).length < Object.keys(b[1]).length)
							return 1;
						if (Object.keys(a[1]).length > Object.keys(b[1]).length)
							return -1;
						return 0
					})
					: Object.entries(sortableBreakdown).sort((a, b) => {
						if (this.zoneData[a[0]].name < this.zoneData[b[0]].name)
							return -1;
						if (this.zoneData[a[0]].name > this.zoneData[b[0]].name)
							return 1;
						return 0;
					})
				;

				// Take the items and remove them from any other zone
				var takenZoneId = sorted[0][0],
					takenItemIds = Object.keys(sortableBreakdown[takenZoneId]);

				sortedZones.push(takenZoneId);

				delete sortableBreakdown[takenZoneId];

				Object.keys(sortableBreakdown).forEach(zoneId => {
					for (let itemId of takenItemIds)
						delete sortableBreakdown[zoneId][itemId];

					if (Object.keys(sortableBreakdown[zoneId]).length == 0) {
						delete sortableBreakdown[zoneId];
						blankZones.push(zoneId);
					}
				});
			}

			for (let z of blankZones)
				sortedZones.push(z);

			return sortedZones;
		},
	},
	methods: {
		...mutations,
		...actions,
		calculateAll:function() {
			this.resetAmountsRequired();
			this.computeAmounts(originItemIds, quantities); // Global vars
		},
		itemsAvailableRecipes:function() {
			var itemsAvailableRecipes = {};
			Object.keys(this.recipeData).forEach(key => {
				if (typeof itemsAvailableRecipes[this.recipeData[key]['item_id']] === 'undefined')
					itemsAvailableRecipes[this.recipeData[key]['item_id']] = [];
				itemsAvailableRecipes[this.recipeData[key]['item_id']].push(key);
			});
			return itemsAvailableRecipes;
		},
		computeAmounts:function(itemIds, loopQtys) {
			// Prefer to gather items in this order
			var preferredHandleOrder   = ['recipes', 'everythingElse'],//nodes', 'shops'],
				itemsAvailableRecipes  = this.itemsAvailableRecipes();

			for (var itemId of itemIds)
			{
				// TODO TICKETME - there's an opportunity to have a preferredHandleOrder on a per item ID basis
				// This loop is broken out of when the answer is hit
				for (var method of preferredHandleOrder)
				{
					if (method == 'recipes' && typeof itemsAvailableRecipes[itemId] !== 'undefined')
					{
						var recipeId = itemsAvailableRecipes[itemId][0];
						if (itemsAvailableRecipes[itemId].length > 1)
						{
							for (var recipeIdCheck of itemsAvailableRecipes[itemId])
							{
								if (preferredRecipeIds.includes(recipeIdCheck))
								{
									recipeId = recipeIdCheck;
									break;
								}
							}
						}

						this.increaseRecipeRequiredAmount(recipeId, parseInt(loopQtys[itemId]));

						this.craftRecipe(recipeId);

						break;
					}
					else
					{
						this.increaseItemRequiredAmount(itemId, parseInt(loopQtys[itemId]));

						break;
					}
				}
			}
		},
		craftRecipe:function(recipeId) {
			var required = this.recipes[recipeId].required,
				alreadyHave = this.recipes[recipeId].have,
				yields   = parseInt(this.recipeData[recipeId].yield),
				itemIds  = [],
				loopQtys = {},
				qtyMultiplier = 1;

			// Quantity Multiplier
			// If we need 4, but the recipe yields 3, then we need to craft twice (for 6), which requires 2x the ingredient quantity
			// But if you already have one of them, don't count it
			qtyMultiplier = Math.ceil((required - alreadyHave) / yields);
			// console.log(this.itemData[this.recipeData[recipeId].item_id].name, required, alreadyHave, qtyMultiplier);

			// console.log('We are crafting recipe', id, 'it yields', yields, 'per craft, and we need', required, 'of them, meaning our multiplier is', qtyMultiplier);

			for (var item of this.recipeData[recipeId].ingredients) {
				itemIds.push(item.id);
				loopQtys[item.id] = item.pivot.quantity * qtyMultiplier;
				// console.log('ITEM', item.name, loopQtys[item.id]);
			}

			this.computeAmounts(itemIds, loopQtys);
		},
		registerItemsAndRecipes:function() {
			Object.keys(this.recipeData).forEach(recipeId => {
				this.setRecipeData(recipeId);
			});
			Object.keys(this.itemData).forEach(itemId => {
				this.setItemData(itemId);
			});
		},
		resetAmountsRequired:function() {
			Object.keys(this.recipeData).forEach(recipeId => {
				this.setRecipeRequiredAmount(recipeId, 0);
			});
			Object.keys(this.itemData).forEach(itemId => {
				this.setItemRequiredAmount(itemId, 0);
			});
		}
	}
});
