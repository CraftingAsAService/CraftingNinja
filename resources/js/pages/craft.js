/**
 * Craft Page
 */

'use strict';

Vue.component('ninja-map', require('../components/NinjaMap.vue').default);

const craft = new Vue({
	name: 'Crafting',
	el: '#craft',
	data: {
		// preferredRecipeIds: preferredRecipeIds,
		// givenItemIds: givenItemIds,
		// quantities: quantities,
		// breakdown: breakdown,
		// items: items,
		// recipes: recipes,
		// nodes: nodes,
		// zones: zones,
		// rewards: rewards,
		// mobs: mobs,
		// shops: shops,
		maps: maps,
		// Crafting loop
		topTierCrafts: {},
		itemsToGather: {},
	},
	created() {
		this.computeAmounts(givenItemIds, quantities);
	},
	mounted() {
		this.$nextTick(() => {
			// // Fake a dynamic add
			// let markers = [
			// 	{
			// 		'id': 111,
			// 		'tooltip': 'Level 65 Rocky Outcrop',
			// 		'x': 20.4,
			// 		'y': 33.3,
			// 		'icon': '/assets/' + game.slug + '/map/icons/spearfishing.png'
			// 	},
			// 	{
			// 		'id': 77,
			// 		'tooltip': 'Level 65 Rocky Outcrop',
			// 		'x': 33.4,
			// 		'y': 15.3,
			// 		'icon': '/assets/' + game.slug + '/map/icons/mining.png'
			// 	}
			// ];

			// this.maps.push({
			// 	id: 222,
			// 	name: 'Central Shroud - Bentbranch',
			// 	src: '/assets/' + game.slug + '/m/r2f1/r2f1.00.jpg',
			// 	// Goes from 1,1 to 44,44 (as opposed to 0,0 to x,y)
			// 	//  anything less than 1,1 is unreachable
			// 	//  44,44 itself is unreachable
			// 	bounds: [[1, 1], [44, 44]],
			// 	markers: markers
			// })
		})
	},
	methods: {
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
				itemsAvailableRecipes  = this.itemsAvailableRecipes(),
				recipesToLoopThisRound = [];

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
							this.topTierCrafts[recipeId].amountRequired += loopQtys[id];
						} else {
							this.topTierCrafts[recipeId] = this.dataTemplate(recipeId, loopQtys[id]);
						}

						recipesToLoopThisRound.push(recipeId);

						break;
					}
					else
					{
						if (typeof this.itemsToGather[id] !== 'undefined') {
							this.itemsToGather[id].amountRequired += loopQtys[id];
						} else {
							this.itemsToGather[id] = this.dataTemplate(id, loopQtys[id]);
						}

						break;
					}
				}
			}

			for (var recipeId of recipesToLoopThisRound) {
				this.craftRecipe(recipeId);
			}

			// console.log(this.topTierCrafts, this.itemsToGather);
		},
		dataTemplate:function(id, quantity) {
			return {
				'id': id,
				'amountHave': 0, // How many you physically have
				'amountNeeded': 0, // How many you currently need (minus completed recipes)
				'amountRequired': parseInt(quantity), // How many you need in absolute total (including completed recipes)
			};
		},
		craftRecipe:function(id) {
			var required = this.topTierCrafts[id].amountRequired,
				alreadyHave = this.topTierCrafts[id].amountHave,
				yields   = recipes[id].yield,
				itemIds  = [],
				loopQtys = {},
				qtyMultiplier = 1;

			// Quantity Multiplier
			// If we need 4, but the recipe yields 3, then we need to craft twice (for 6), which requires 2x the ingredient quantity
			// But if you already have one of them, don't count it
			qtyMultiplier = Math.ceil((required - alreadyHave) / yields);
			console.log('We are crafting recipe', id, 'it yields', yields, 'per craft, and we need', required, 'of them, meaning our multiplier is', qtyMultiplier);

			for (var item of recipes[id].ingredients) {
				itemIds.push(item.id);
				loopQtys[item.id] = item.pivot.quantity * qtyMultiplier;
			}

			this.computeAmounts(itemIds, loopQtys);
		}
	}
});
