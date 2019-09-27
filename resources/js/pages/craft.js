/**
 * Craft Page
 */

'use strict';

Vue.component('ninja-map', require('../components/NinjaMap.vue').default);

const compendium = new Vue({
	el: '#craft',
	data: {
		size: 577,
		mapName: 'Central Shroud - Bentbranch',
		markers: [
			{
				'id': 111,
				'tooltip': 'Level 65 Rocky Outcrop',
				// 'spawn': {},
				// 'star': 0,
				'position': { lat: -200, lng: 100 },
				'icon': 'spearfishing'
			}
		],
	}
});
