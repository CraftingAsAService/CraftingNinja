<template>
	<div class='row item'>
		<div class='col-auto'>
			<img :src='"/assets/" + gameSlug + "/i/" + item.icon + ".png"' alt='' width='48' height='48' class='icon'>
		</div>
		<div class='col info' :style='need <= 0 ? "opacity: .5;" : ""'>
			<span class='required text-warning' v-if='need > 0' v-html='need'></span>
			<small class='text-muted' v-if='need > 0'>x</small>
			<big :class='"rarity-" + item.rarity' v-html='item.name'></big>
		</div>
		<div class='col-auto'>
			<div class='form-group tally'>
				<label class='checkbox ml-2' style='width: 24px;'>
					<input type='checkbox' v-model='checked'>
					<span class='checkbox-indicator' style='width: 24px; height: 24px; top: -10px;'></span>
				</label>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: [ 'recipe', 'item' ],
		data () {
			return {
				gameSlug: game.slug,
				nodeTypes: nodeTypes,
				nodes: nodes,
				sources: {},
				progress: 0,
				checked: false,
				need: 0,
				have: 0,
				required: 0
			}
		},
		created:function() {
			this.$eventBus.$on('recipe' + this.item.id + 'data', this.amountUpdate);
		},
		// mounted:function() {
		// },
		beforeDestroy:function() {
			this.$eventBus.$off('recipe' + this.item.id + 'data');
		},
		watch: {
			checked:function(truthy) {
				this.$emit('pass-have-recipe-to-parent', this.recipe.id, truthy);
			}
		},
		methods: {
			amountUpdate:function(need, have, required) {
				this.need = need;
				this.have = have;
				this.required = required;
			}
		}
	}
</script>
