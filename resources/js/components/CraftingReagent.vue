<template>
	<div class='row item'>
		<div class='col-auto'>
			<img :src='"/assets/" + gameSlug + "/i/" + item.icon + ".png"' alt='' width='48' height='48' class='icon'>
		</div>
		<div class='col info' :style='need <= 0 ? "opacity: .5;" : ""'>
			<span class='required text-warning' v-if='need > 0' v-html='need'></span>
			<small class='text-muted' v-if='need > 0'>x</small>
			<big :class='"rarity-" + item.rarity' v-html='item.name'></big>

			<div class='sources'>
				<img v-for='(node, nodeId) in sources.nodes' :src='"/assets/" + gameSlug + "/map/icons/" + nodeTypes[nodes[nodeId].type].icon + ".png"' alt='' data-toggle='tooltip' :data-title='"Level " + nodes[nodeId].level + ", " + nodeTypes[nodes[nodeId].type].name'>
				<!--
				<div class='card p-2 mt-2' hidden>
					@foreach ($itemData['nodes'] ?? [] as $nodeId => $data)
					<i class='fas fa-caret-square-up text-primary'></i>
					<div>
						<img src='/assets/{{ config('game.slug') }}/map/icons/{{ config('game.nodeTypes')[$nodes[$nodeId]['type']]['icon'] }}.png' alt=''> <code>{{ $data['x'] }},{{ $data['y'] }}</code> {{ config('game.nodeTypes')[$nodes[$nodeId]['type']]['name'] }}{{--  - <code>55%</code> --}}
					</div>
					@endforeach
				</div>
				-->
			</div>
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
		props: [ 'item', 'sources' ],
		data () {
			return {
				gameSlug: game.slug,
				nodeTypes: nodeTypes,
				nodes: nodes,
				checked: false,
				need: 0,
				have: 0,
				required: 0
			}
		},
		created:function() {
			this.$eventBus.$on('item' + this.item.id + 'data', this.amountUpdate);
		},
		// mounted:function() {
		// },
		beforeDestroy:function() {
			this.$eventBus.$off('item' + this.item.id + 'data');
		},
		watch: {
			checked:function(truthy) {
				this.$emit('pass-have-item-to-parent', this.item.id, truthy);
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
