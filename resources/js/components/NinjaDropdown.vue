<template>
	<!-- <ninja-dropdown title='Filter By' icon='fas fa-filter' placeholder='Add Filter'></ninja-dropdown> -->
	<div class='post-filter__select ninja-dropdown' v-click-outside='close'>
		<label class='post-filter__label'>
			<i :class='icon + " mr-1"'></i>
			{{ title }}
		</label>
		<div :class='"cs-select cs-skin-border " + (open ? " cs-active" : "")'>
			<span class='cs-placeholder' v-on:click='open = ! open'>{{ placeholder || displayTitle }}</span>
			<ul class='cs-options'>
				<li v-for='optionData in options' v-on:click='optionClick(option, optionData["key"], optionData["title"])'>
					<span>
						<i :class='"fas " + optionData["icon"] + " fa-fw mr-1"'></i>
						{{ optionData["title"] }}
					</span>
				</li>
			</ul>
		</div>
	</div>
</template>

<script>
	export default {
		props: [ 'title', 'icon', 'placeholder', 'option', 'options' ],
		data:function() {
			return {
				displayTitle: null,
				open: false
			}
		},
		mounted:function() {
			this.displayTitle = this.options[0].title;
		},
		methods: {
			close:function() {
				this.open = false;
			},
			optionClick:function(key, value, title) {
				this.displayTitle = title;
				this.$emit('clicked', key, value)
				this.close();
			}
		}
	}
</script>
