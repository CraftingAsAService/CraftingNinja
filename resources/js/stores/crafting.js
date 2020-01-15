// export const storex = new Vuex.Store({
// 	state: {
// 		variable: 0
// 	},
// 	getters: {
// 		someFilter: state => {
// 			return state.myVariable[0]
// 		}
// 	},
// 	// Mutations are Syncronous
// 	mutations: {
// 		addToArray(state, value) {
// 			state.myVariable.push(value)
// 		}
// 	},
// 	// Actions are Asyncronous
// 	// actions: {
// 	// 	addToArray(context) { // Matches a mutation function name
// 	// 		context.commit('addToArray');
// 	// 	}
// 	// }
// })

export const store = Vue.observable({
	items: {},
	recipes: {},
	activeMap: null,
	activeItemRecipes: {
		// 123 => { 456: 3, 789: 1 }, // Item 123 is crafted using 3 of 456 and 1 of 789
	},
	activeItemZones: {
		// 123 => 456, // Item 123 is only to be viewed in 456
	},
	// zoneItemCount: { 123 => 1 }, // Zone 123 has one item
	// zoneItemCount: {},
});

export const mutators = {
	firstComeFirstServeItemToZone:function(itemId, zoneId) {
		if (typeof store.activeItemZones[itemId] === 'undefined')
			mutators.setItemToZone(itemId, zoneId);
	},
	setItemToZone:function(itemId, zoneId) {
		store.activeItemZones[itemId] = zoneId;
		mutators.recalculateZoneItemCounts();
	},
	recalculateZoneItemCounts:function() {
		var counts = {};
		Object.entries(store.activeItemZones).forEach(([itemId, zoneId]) => {
			counts[zoneId] = counts[zoneId] ? counts[zoneId] + 1 : 1;
		});
		Object.entries(counts).forEach(([zoneId, count]) => {
			Vue.set(store, 'zoneItemCount' + zoneId, count);
		});
		console.log(store);
	},
	updateRawRecipeAmounts:function(id, need, have, required) {
		if (typeof store.recipes[id] === 'undefined')
			store.recipes[id] = {
				need:     0,
				have:     0,
				required: 0
			};
		store.recipes[id].need = need;
		store.recipes[id].have = have;
		store.recipes[id].required = required;
	},
	updateRawItemAmounts:function(id, need, have, required) {
		if (typeof store.items[id] === 'undefined')
			store.items[id] = {
				need:     0,
				have:     0,
				required: 0
			};
		store.items[id].need = need;
		store.items[id].have = have;
		store.items[id].required = required;
	}
};
