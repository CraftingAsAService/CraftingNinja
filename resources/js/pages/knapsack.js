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
				if (this.active)
					this.$eventBus.$emit('updateCart', this.active.id, this.active.type, this.active.quantity);
			},
			deep: true
		}
	},
	created:function() {
		this.$eventBus.$on('cartChanged', this.cartChanged);
	},
	beforeDestroy:function() {
		this.$eventBus.$off('cartChanged');
	},
	methods: {
		cartChanged:function(cartContents) {
			// Align local cart data with the actual cart contents
			var newContents = [],
				oldContents = this.contents;

			for (var index in oldContents)
			{
				var cIndex = cartContents.findIndex(function(entry) {
					return entry.i == oldContents[index].id;
				});

				if (cIndex !== -1) {
					oldContents[index].quantity = cartContents[cIndex].q;
					newContents.push(oldContents[index]);
				}
			}

			this.contents = newContents;
		},
		updateCart:function() {
			this.$eventBus.$emit('updateCart', this.contents);
			this.$eventBus.$emit('addToCart', this.id, this.type, 1, this.img, this.$refs.ninjaBagButton);
		},
		removeFromCart:function(id, type) {
			this.deactivate();
			this.$eventBus.$emit('removeFromCart', id, type);
		},
		clearCart:function() {
			this.deactivate();
			this.$eventBus.$emit('clearCart');
		},
		activate:function(index) {
			this.active = this.contents[index];
		},
		deactivate:function() {
			this.active = null;
		}
	}
});
