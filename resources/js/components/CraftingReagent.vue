<template>
	<div class='row item' v-show='shown'>
		<div class='col-auto'>
			<img :src='"/assets/" + game.slug + "/i/" + item.icon + ".png"' alt='' width='48' height='48' class='icon'>
		</div>
		<div class='col info' :style='item.need <= 0 ? "opacity: .5;" : ""'>
			<span class='required text-warning' v-if='item.need > 0' v-html='item.need'></span>
			<small class='text-muted' v-if='item.need > 0'>x</small>
			<big :class='"rarity-" + item.rarity' v-html='item.name'></big>

			<div class='sources'>
				<template v-for='(sourceTypes, sourceZoneId) in sources'>
					<template v-for='(sourceData, type) in sourceTypes'>
						<crafting-source v-for='(info, id) in sourceData' :key='sourceZoneId + type + id' :zone-matches='zoneId == sourceZoneId' :zone-id='sourceZoneId' :item-id='item.id' :type='type' :id='id' :info='info'></crafting-source>
					</template>
				</template>
				<!--
				<div class='card p-2 mt-2' hidden>
					@foreach ($itemData['nodes'] ?? [] as $nodeId => $data)
					<i class='fas fa-caret-square-up text-primary'></i>
					<div>
						<img src='/assets/{{ config('game.slug') }}/map/icons/{{ config('game.nodeTypes')[$nodeData[$nodeId]['type']]['icon'] }}.png' alt=''> <code>{{ $data['x'] }},{{ $data['y'] }}</code> {{ config('game.nodeTypes')[$nodeData[$nodeId]['type']]['name'] }}{{--  - <code>55%</code> --}}
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
	import { getters, mutations, actions } from '../stores/crafting';

	Vue.component('crafting-source', require('../components/CraftingSource.vue').default);

	export default {
		props: [ 'itemId', 'zoneId' ],
		data() {
			return {
				shown: true,
				checked: false
			}
		},
		created() {
			// Calculate if its shown
			this.shown = actions.fcfsItemZonePreference(this.itemId, this.zoneId);
		},
		computed: {
			item() {
				return {
					...this.itemData[this.itemId],  // "Official" item data, name/icon/etc
					...getters.items()[this.itemId] // "Crafting" item data, have/need/required
				};
			},
			sources() {
				// Ensure that the current zones sources are first
				let sources = {};
				sources[this.zoneId] = this.breakdown[this.zoneId][this.itemId];
				return { ...sources, ...this.otherSources };
			},
			otherSources() {
				var alternateSources = {};
				Object.keys(this.breakdown).forEach(loopedZoneId => {
					if (loopedZoneId == this.zoneId)
						return;
					Object.keys(this.breakdown[loopedZoneId]).forEach(loopedItemId => {
						if (loopedItemId == this.itemId)
							alternateSources[loopedZoneId] = this.breakdown[loopedZoneId][this.itemId];
					});
				});
				return alternateSources;
			},
		},
		watch: {
			checked:function(truthy) {
				console.log('checked!');
				// this.$emit('pass-have-item-to-parent', this.itemId, truthy);
			}
		},
		methods: {
			// ...mutations,
			// amountUpdate:function(need, have, required) {
			// 	this.need = need;
			// 	this.have = have;
			// 	this.required = required;
			// }
		}
	}
</script>
