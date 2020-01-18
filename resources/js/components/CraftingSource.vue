<template>
	<img :src='src' alt='' data-toggle='tooltip' :data-title='title' :class='zoneMatches ? "" : "opaque"'>
</template>

<script>
	// import { store, mutators } from "../stores/crafting";

	export default {
		props: [ 'zoneMatches', 'type', 'id', 'info' ],
		computed: {
			src() {
				if (this.type == 'node')
					return "/assets/" + game.slug + "/map/icons/" + this.nodeTypes[this.nodeData[this.id].type].icon + ".png";
			},
			title() {
				if (this.type == 'node')
					return "Level " + this.nodeData[this.id].level + ", " + this.nodeTypes[this.nodeData[this.id].type].name;
			}
		}
	}
</script>
