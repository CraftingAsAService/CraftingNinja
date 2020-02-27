<template>
	<img :src='src' alt='' data-toggle='tooltip' data-html='true' :data-title='title' :style='"vertical-align: top;" + (parentMatches ? "" : "opacity: .5; cursor: pointer;")' @click='switchZone()'>
</template>

<script>
	import { getters, mutations } from '../stores/crafting';

	export default {
		// Parent ID is either the Job or Zone ID
		props: [ 'sectionParentId', 'type', 'id', 'parentId', 'itemId', 'info' ],
		computed: {
			...getters,
			parentMatches() {
				return this.sectionParentId == this.parentId;
			},
			src() {
				if (this.type == 'node')
					return '/assets/' + game.slug + '/map/icons/' + this.nodeTypes[this.nodeData[this.id].type].icon + '.png';
				else if (this.type == 'mob')
					return '/assets/' + game.slug + '/map/icons/battle.png';
				else if (this.type == 'shop')
					return '/assets/' + game.slug + '/map/icons/vendor.png';
				else if (this.type == 'reward')
					return '/assets/' + game.slug + '/map/icons/landmark.png';
			},
			title() {
				let title = '',
					prefix = '';

				if ( ! this.parentMatches)
					prefix = this.zoneData[this.parentId].name + ':<br>';

				if (this.type == 'node')
					title = 'Level ' + this.nodeData[this.id].level + ', ' + this.nodeTypes[this.nodeData[this.id].type].name;
				else if (this.type == 'mob')
					title = 'Level ' + this.mobData[this.id].level + ', ' + this.mobData[this.id].name;

				return prefix + title;
			}
		},
		methods: {
			switchZone() {
				if (this.parentMatches)
					return;

				mutations.setItemZonePreference(this.itemId, this.parentId);

				// this.$eventBus.$emit('zoneRefresh', this.parentId);
				// this.$eventBus.$emit('zoneRefresh', this.sectionParentId);
				this.$eventBus.$emit('craftRefresh');
			}
		}
	}
</script>
