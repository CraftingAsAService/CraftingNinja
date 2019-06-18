<template>
	<!-- <ninja-bag-button text='Add to bag' icon='icon-bag' type='recipe' id='1234'></ninja-dropdown> -->
	<a href='#' class='btn btn-primary-inverse btn-block btn-icon' @click.prevent='addToBag'><i :class='icon'></i> <span v-html='text'></span></a>
</template>

<script>
	export default {
		props: [ 'text', 'icon', 'type', 'id' ],
		methods: {
			addToBag:function() {
				axios
					.post('/knapsack', {
						'id': this.id,
						'type': this.type,
						'quantity': 1
					})
					.then(response => {
						// bag.refresh();
					})
					.catch(error => console.log(error));
			}
		}
	}
</script>
