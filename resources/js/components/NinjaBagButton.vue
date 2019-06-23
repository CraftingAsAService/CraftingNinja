<template>
	<!-- <ninja-bag-button text='Add to bag' icon='icon-bag' type='recipe' id='1234' img='/path/to/img.jpg'></ninja-bag-button> -->
	<a href='#' ref='ninjaBagButton' class='btn btn-primary-inverse btn-block btn-icon' @click.prevent='add'><i :class='icon'></i> <span v-html='text'></span></a>
</template>

<script>
	export default {
		props: [ 'text', 'icon', 'type', 'id', 'img' ],
		methods: {
			add:function() {
				this.$eventBus.$emit('addToCart', this.id, this.type, 1, this.img, this.$refs.ninjaBagButton);
			}
		}
	}
</script>
