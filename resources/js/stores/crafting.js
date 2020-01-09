export const store = Vue.observable({
	items: {},
	recipes: {},
	activeMap: null,
	activeItemRecipes: {
		// 123 => { 456: 3, 789: 1 }, // Item 123 is crafted using 3 of 456 and 1 of 789
	},
	activeItemZones: {
		// 123 => 456, // Item 123 is only to be viewed in 456
	}
});

export const mutators = {
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
