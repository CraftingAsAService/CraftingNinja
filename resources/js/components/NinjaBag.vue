<template>
	<!-- <ninja-bag text='Add to bag' icon='icon-bag' type='recipe' id='1234'></ninja-dropdown> -->
	<a href='#' class='btn btn-primary-inverse btn-block btn-icon' @click.prevent='addToBag'><i :class='icon'></i> <span v-html='text'></span></a>
</template>

<script>
	export default {
		props: [ 'text', 'icon', 'type', 'id' ],
		data:function() {
			return {

			}
		},
		mounted:function() {

		},
		methods: {
			addToBag:function() {
				console.log('hello', this.type, this.id);
			}
		}
	}
</script>
