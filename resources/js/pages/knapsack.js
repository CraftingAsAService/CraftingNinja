/**
 * Knapsack Page
 */

'use strict';

const knapsack = new Vue({
	el: '#knapsack',
	data: {
		contents: ninjaCartContents
	},
	methods: {
		removeFromCart:function(entry) {
			console.log(entry);
		}
	}
});
