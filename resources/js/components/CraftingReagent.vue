<template>
	<!-- <crafting-reagent itemId='12345'></crafting-reagent> -->
	<div class='row item'>
		<div class='col-auto'>
			<img :src='"/assets/" + gameSlug + "/i/" + icon + ".png"' alt='' width='48' height='48' class='icon'>
		</div>
		<div class='col info' :style='itemsToGather[itemId].need <= 0 ? "opacity: .5;" : ""'>
			<span class='required text-warning' v-if='itemsToGather[itemId].need > 0' v-html='itemsToGather[itemId].need'></span>
			<small class='text-muted' v-if='itemsToGather[itemId].need > 0'>x</small>
			<big :class='"rarity-" + rarity' v-html='itemName'></big>

			<div class='sources'>
				<img v-if='typeof sources.nodes !== "undefined"' v-for='(node, nodeId) in sources.nodes' :src='"/assets/" + gameSlug + "/map/icons/" + nodeTypes[nodes[nodeId].type].icon + ".png"' alt='' data-toggle='tooltip' :data-title='"Level " + nodes[nodeId].level + ", " + nodeTypes[nodes[nodeId].type].name'>
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
		props: [ 'itemId', 'itemData', 'itemName', 'itemsToGather' ],
		data () {
			return {
				gameSlug: game.slug,
				nodeTypes: nodeTypes,
				nodes: nodes,
				sources: {},
				icon: '',
				rarity: '',
				checked: false
			}
		},
		mounted:function() {
			this.icon = items[this.itemId].icon;
			this.rarity = items[this.itemId].rarity;

			this.sources = JSON.parse(this.itemData);
		},
		created:function() {
			// console.log('created');
			// this.$eventBus.$on('reagentAmountsUpdated', this.amountUpdate);
		},
		beforeDestroy:function() {
			// console.log('beforeDestroy');
			// this.$eventBus.$off('reagentAmountsUpdated');
		},
		watch: {
			checked:function(truthy) {
				this.$emit('pass-have-item-to-parent', this.itemId, truthy);
			}
		},
		methods: {
			// amountUpdate:function(a, b, c, allAmounts) {
			// 	console.log(a, b, c);

			// 	this.have = allAmounts[this.itemId].have;
			// 	this.need = allAmounts[this.itemId].need;
			// 	this.required = allAmounts[this.itemId].required;
			// }
		}
	}
</script>
