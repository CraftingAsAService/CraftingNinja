<template>
	<!-- <ninja-bag-button text='Add to bag' icon='icon-bag' type='recipe' id='1234' img='/path/to/img.jpg'></ninja-bag-button> -->
	<a href='#' ref='ninjaBagButton' :class='"btn btn-icon" + (typeof text !== "undefined" ? " btn-primary-inverse btn-block" : "")' @click.prevent='add'><i :class='icon'></i> <span v-html='text' v-if='typeof text !== "undefined"'></span></a>
</template>

<script>
	export default {
		props: [ 'text', 'icon', 'type', 'id', 'img', 'list' ],
		methods: {
			add:function() {
				if (typeof this.list !== 'undefined') {
					for (var chapter in this.list) {
						if (this.list.hasOwnProperty(chapter)) {
							for (var index in this.list[chapter]) {
								if (this.list[chapter].hasOwnProperty(index)) {
									var entity = this.list[chapter][index],
										type = chapter.replace(/s$/, ''),
										img = "/assets/" + game.slug + "/" + type + "/" + entity.icon + ".png";

									this.$eventBus.$emit('addToCart', entity.id, type, entity.pivot.quantity, img, this.$refs.ninjaBagButton);
								}
							}
						}
					}
				} else
					this.$eventBus.$emit('addToCart', this.id, this.type, 1, this.img, this.$refs.ninjaBagButton);
			}
		}
	}
</script>
