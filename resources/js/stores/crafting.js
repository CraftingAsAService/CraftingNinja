// https://austincooper.dev/2019/08/09/vue-observable-state-store/
const state = Vue.observable({
	items: {},
	recipes: {},
	itemZonePreferences: {},
});

export const getters = {
	items: () => state.items,
	recipes: () => state.recipes,
	itemZonePreferences: () => state.itemZonePreferences,
};

export const mutations = {
	setItemData: (itemId, need, have, required) => state.items[itemId] = {
			need:     need,
			have:     have,
			required: required
		},
	setRecipeData: (recipeId, need, have, required) => state.recipes[recipeId] = {
			need:     need,
			have:     have,
			required: required
		},
	setItemZonePreference: (itemId, zoneId) => state.itemZonePreferences[itemId] = zoneId,
}

export const actions = {

}

// export const mutators = {
// 	firstComeFirstServeItemToZone:function(itemId, zoneId) {
// 		if (typeof store.activeItemZones[itemId] === 'undefined')
// 			mutators.setItemToZone(itemId, zoneId);
// 	},
// 	setItemToZone:function(itemId, zoneId) {
// 		store.activeItemZones[itemId] = zoneId;
// 		mutators.recalculateZoneItemCounts();
// 	},
// 	recalculateZoneItemCounts:function() {
// 		var counts = {};
// 		Object.entries(store.activeItemZones).forEach(([itemId, zoneId]) => {
// 			counts[zoneId] = counts[zoneId] ? counts[zoneId] + 1 : 1;
// 		});
// 		Object.entries(counts).forEach(([zoneId, count]) => {
// 			Vue.set(store, 'zoneItemCount' + zoneId, count);
// 		});
// 		console.log(store);
// 	},
// };
