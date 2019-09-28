/**
 * Craft Page
 */

'use strict';

Vue.component('ninja-map', require('../components/NinjaMap.vue').default);

const compendium = new Vue({
	el: '#craft',
	data: {
		mapName: 'Central Shroud - Bentbranch',
		markers: [
			{
				'id': 111,
				'tooltip': 'Level 65 Rocky Outcrop',
				// 'spawn': {},
				// 'star': 0,
				'x': 20.4,
				'y': 33.3,
				'icon': 'spearfishing'
			},
			{
				'id': 77,
				'tooltip': 'Level 65 Rocky Outcrop',
				// 'spawn': {},
				// 'star': 0,
				'x': 33.4,
				'y': 15.3,
				'icon': 'mining'
			}
		],
	}
});
