// https://austincooper.dev/2019/08/09/vue-observable-state-store/
const state = Vue.observable({
	items: {},
	recipes: {},
	itemZonePreferences: {},
	recipeJobTierPreferences: {},
});

export const getters = {
	items: () => state.items,
	itemZonePreferences: () => state.itemZonePreferences,
	recipes: () => state.recipes,
	recipeJobTierPreferences: () => state.recipeJobTierPreferences,
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
	setRecipeJobTierPreference: (recipeId, jobId, tierId) => state.recipeJobTierPreferences[recipeId] = jobId + '-' + tierId,
}

export const actions = {
	fcfsItemZonePreference: (itemId, zoneId) => {
		// First Come, First Serve the Item-Zone Preference
		if (typeof state.itemZonePreferences[itemId] !== 'undefined')
			return state.itemZonePreferences[itemId] == zoneId;

		mutations.setItemZonePreference(itemId, zoneId);
		return true;
	},
	fcfsRecipeJobTierPreference: (recipeId, jobId, tierId) => {
		// First Come, First Serve the Recipe-Job Preference
		if (typeof state.recipeJobTierPreferences[recipeId] !== 'undefined')
			return state.recipeJobTierPreferences[recipeId] == jobId + '-' + tierId;

		mutations.setRecipeJobTierPreference(recipeId, jobId, tierId);
		return true;
	},
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
