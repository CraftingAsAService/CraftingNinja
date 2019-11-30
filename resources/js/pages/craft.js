/**
 * Craft Page
 */

'use strict';

Vue.component('ninja-map', require('../components/NinjaMap.vue').default);

const craft = new Vue({
	name: 'Crafting',
	el: '#craft',
	data: {
		preferredRecipeIds: preferredRecipeIds,
		givenItemIds: givenItemIds,
		breakdown: breakdown,
		items: items,
		recipes: recipes,
		nodes: nodes,
		zones: zones,
		rewards: rewards,
		mobs: mobs,
		shops: shops,
		maps: maps,
	},
	created() {
		this.computeAmounts();
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
		computeAmounts:function() {
			// We want these items: givenItemIds
			// If any of them can be recipe'd, do it, otherwise it'll have to come from a drop
			var topTierCrafts = {};
			for (var id in givenItemIds)
			{
				console.log(givenItemIds);
			}
		}
	}
});
