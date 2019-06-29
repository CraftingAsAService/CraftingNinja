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
		removeFromCart:function(index) {
			this.contents.splice(index, 1);
			this.$eventBus.$emit('removeFromCart', index, 'index');
		}
	}
});
