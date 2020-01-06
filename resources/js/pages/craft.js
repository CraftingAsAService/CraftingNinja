/**
 * Craft Page
 */

'use strict';

Vue.component('ninja-map', require('../components/NinjaMap.vue').default);
Vue.component('crafting-reagent', require('../components/CraftingReagent.vue').default);
// Vue.component('crafting-recipe', require('../components/CraftingRecipe.vue').default);

const craft = new Vue({
	name: 'Crafting',
	el: '#craft',
	data: {
		breakdown: breakdown,
		zones: zones,
		items: items,
		recipes: recipes,
		recipeJobs: recipeJobs,
		maps: maps,
		// preferredRecipeIds: preferredRecipeIds,
		// givenItemIds: givenItemIds,
		// quantities: quantities,
		// nodes: nodes,
		// rewards: rewards,
		// mobs: mobs,
		// shops: shops,
		activeMap: 0,
		// Crafting loop
		topTierCrafts: {},
		itemsToGather: {},
		sortedBreakdown: {},
	},
	created() {
		this.registerItems();
		this.calculateSortedBreakdown();
	},
	mounted() {
		// Calling calculateAll in mounted() allows all the crafting-reagent/recipes to hit their respective created() calls first
		this.calculateAll();
	},
	methods: {
		calculateSortedBreakdown:function() {
			// TODO let user decide how they want items sorted
			// Group by most available?
			// Group by zone names?

			// Primary Objective, Sort them by how many items a zone can provide
			function reverseCount(a, b) {
				var a = Object.values(breakdown[a]).length,
					b = Object.values(breakdown[b]).length;
				if (a < b)
					return 1;
				if (a > b)
					return -1;
				return 0;
			}

			// Secondary Objective, if they have the same amount, sort them by name
			//  This hopefully keeps zone-adjacent areas together to avoid confusion
			function nameSort(a, b) {
				if (zones[a].name < zones[b].name)
					return -1;
				if (zones[a].name > zones[b].name)
					return 1;
				return 0;
			}

			// Order of operations says we should sort by name first, then by the number of items to achieve this
			this.sortedBreakdown = Object.keys(this.breakdown).sort(nameSort).sort(reverseCount);
		},
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
			Object.keys(recipes).forEach(key => {
				if (typeof itemsAvailableRecipes[recipes[key]['item_id']] === 'undefined')
					itemsAvailableRecipes[recipes[key]['item_id']] = [];
				itemsAvailableRecipes[recipes[key]['item_id']].push(key);
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
				this.$eventBus.$emit('recipe' + entry.id + 'data', entry.need, entry.have, entry.required);
			});
			Object.entries(this.itemsToGather).forEach(([key, entry]) => {
				entry.need = Math.max(0, entry.required - entry.have);
				this.$eventBus.$emit('item' + entry.id + 'data', entry.need, entry.have, entry.required);
			});
		}
	}
});
