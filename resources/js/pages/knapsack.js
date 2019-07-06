/**
 * Knapsack Page
 */

'use strict';

const knapsack = new Vue({
	el: '#knapsack',
	data: {
		contents: ninjaCartContents || [],
		active: null
	},
	watch: {
		active: {
			handler:function() {
				this.$eventBus.$emit('updateCart', this.active.id, this.active.type, this.active.quantity);
			},
			deep: true
		}
	},
	methods: {
		updateCart:function() {
			console.log('updateCartPre');
			this.$eventBus.$emit('updateCart', this.contents);
			this.$eventBus.$emit('addToCart', this.id, this.type, 1, this.img, this.$refs.ninjaBagButton);
		},
		removeFromCart:function(index) {
			this.contents.splice(index, 1);
			this.$eventBus.$emit('removeFromCart', index, 'index');
		},
		clearCart:function() {
			this.contents = [];
			this.$eventBus.$emit('clearCart');
		},
		activate:function(index) {
			this.active = ninjaCartContents[index];
		}
	}
});
