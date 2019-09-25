/**
 * Craft Page
 */

'use strict';

Vue.component('ninja-map', require('../components/NinjaMap.vue').default);

const compendium = new Vue({
	el: '#craft',
	data: {
		size: 577,
		mapName: 'Central Shroud - Bentbranch'
	}
});
