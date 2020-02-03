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
			recipeJobs: recipeJobs,
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
		refresh() {
			console.log('Refreshing component', this);
			this.$forceUpdate();
		}
	}
});

Vue.component('crafting-map', require('../components/CraftingMap.vue').default);
Vue.component('crafting-zone', require('../components/CraftingZone.vue').default);
Vue.component('crafting-job', require('../components/CraftingJob.vue').default);

const craft = new Vue({
	name: 'Crafting',
	el: '#craft',
	data() {
		return {
			activeMap: 0,
			topTierCrafts: {},
			itemsToGather: {},
			sortedBreakdown: {},
			sortZonesBy: 'efficiency', // 'alphabetical',
		}
	},
	created() {
		this.registerItems();
		this.calculateAll();
		// this.$eventBus.$on('craftRefresh', this.craftRefresh);
	},
	// beforeDestroy() {
	// 	this.$eventBus.$off('craftRefresh');
	// },
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
		// craftRefresh() {
		// 	console.log('refreshing!');
		// 	this.$forceUpdate();
		// },
		registerItems:function() {
			this.computeAmounts(givenItemIds, quantities);
		},
		haveItem:function(itemId, truthy) {
			if (truthy)
				this.itemsToGather[itemId].have = this.itemsToGather[itemId].required;
			else
				this.itemsToGather[itemId].have = 0;

			this.calculateAll();
		},
		haveRecipe:function(recipeId, truthy) {
			if (truthy)
				this.topTierCrafts[recipeId].have = this.topTierCrafts[recipeId].required;
			else
				this.topTierCrafts[recipeId].have = 0;

			this.calculateAll();
		},
		calculateAll:function() {
			this.resetAmountsRequired();
			this.computeAmounts(givenItemIds, quantities);
			this.recalculateAmountsNeeded();
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

			for (var id of itemIds)
			{
				// TODO TICKETME - there's an opportunity to have a preferredHandleOrder on a per item ID basis
				// This loop is broken out of when the answer is hit
				for (var method of preferredHandleOrder)
				{
					if (method == 'recipes' && typeof itemsAvailableRecipes[id] !== 'undefined')
					{
						var recipeId = itemsAvailableRecipes[id][0];
						if (itemsAvailableRecipes[id].length > 1)
						{
							for (var recipeIdCheck of itemsAvailableRecipes[id])
							{
								if (preferredRecipeIds.includes(recipeIdCheck))
								{
									recipeId = recipeIdCheck;
									break;
								}
							}
						}
						if (typeof this.topTierCrafts[recipeId] !== 'undefined') {
							this.topTierCrafts[recipeId].required += parseInt(loopQtys[id]);
						} else {
							this.topTierCrafts[recipeId] = this.dataTemplate(recipeId, loopQtys[id]);
						}

						this.craftRecipe(recipeId);

						break;
					}
					else
					{
						if (typeof this.itemsToGather[id] !== 'undefined') {
							this.itemsToGather[id].required += parseInt(loopQtys[id]);
						} else {
							this.itemsToGather[id] = this.dataTemplate(id, loopQtys[id]);
						}

						break;
					}
				}
			}
		},
		dataTemplate:function(id, quantity) {
			return {
				'id': id,
				'have': 0, // How many you physically have
				'need': 0, // How many you currently need (minus completed recipes)
				'required': parseInt(quantity), // How many you need in absolute total (including completed recipes)
			};
		},
		craftRecipe:function(id) {
			var required = this.topTierCrafts[id].required,
				alreadyHave = this.topTierCrafts[id].have,
				yields   = parseInt(recipes[id].yield),
				itemIds  = [],
				loopQtys = {},
				qtyMultiplier = 1;

			// Quantity Multiplier
			// If we need 4, but the recipe yields 3, then we need to craft twice (for 6), which requires 2x the ingredient quantity
			// But if you already have one of them, don't count it
			qtyMultiplier = Math.ceil((required - alreadyHave) / yields);

			// console.log('We are crafting recipe', id, 'it yields', yields, 'per craft, and we need', required, 'of them, meaning our multiplier is', qtyMultiplier);

			for (var item of recipes[id].ingredients) {
				itemIds.push(item.id);
				loopQtys[item.id] = item.pivot.quantity * qtyMultiplier;
			}

			this.computeAmounts(itemIds, loopQtys);
		},
		resetAmountsRequired:function() {
			Object.entries(this.topTierCrafts).forEach(([key, entry]) => {
				entry.required = 0;
			});
			Object.entries(this.itemsToGather).forEach(([key, entry]) => {
				entry.required = 0;
			});
		},
		recalculateAmountsNeeded:function() {
			Object.entries(this.topTierCrafts).forEach(([key, entry]) => {
				entry.need = Math.max(0, entry.required - entry.have);
				this.setRecipeData(entry.id, entry.need, entry.have, entry.required);
			});
			Object.entries(this.itemsToGather).forEach(([key, entry]) => {
				entry.need = Math.max(0, entry.required - entry.have);
				this.setItemData(entry.id, entry.need, entry.have, entry.required);
			});
		}
	}
});
