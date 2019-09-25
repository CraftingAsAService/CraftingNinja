<template>
	<l-map
		style='background-color: #392d49; border-radius: 4px; border: 1px solid #4b3b60;'
		ref='map'
		:options='mapOptions'
		:min-zoom='minZoom'
		:max-zoom='maxZoom'
		:crs='crs'
		:center='center'
		:noWrap='noWrap'
		:max-bounds='bounds'
	>
		<l-image-overlay
			:url='mapImage'
			:attribution='attribution'
			:bounds='bounds'
		/>
		<l-control-zoom :position='zoomPosition' />
		<l-control-attribution
			:position='attributionPosition'
			:prefix='attributionPrefix'
		/>
	</l-map>
</template>

<script>
	import { LMap, LImageOverlay, LControlAttribution, LControlZoom, LMarker } from 'vue2-leaflet'
	import { CRS, Icon } from 'leaflet'
	import 'leaflet/dist/leaflet.css'

	export default {
		name: 'ninjamap',
		props: [ 'size', 'mapName' ],
		components: {
			LMap, LImageOverlay, LControlAttribution, LControlZoom, LMarker
		},
		data() {
			return {
				mapImage: '/assets/' + game.slug + '/m/r2f1/r2f1.00.jpg',
				mapOptions: {
					zoomControl: false,
					attributionControl: false,
					zoomSnap: true
				},
				bounds: [[0, 0], [this.size, this.size]],
				center: [this.size / 2, this.size / 2],
				minZoom: 0,
				maxZoom: 3,
				crs: CRS.Simple,
				noWrap: true,
				zoomPosition: 'topleft',
				attribution: '27.1, 35.2',
				attributionPosition: 'bottomright',
				attributionPrefix: this.mapName
			}
		},
		mounted () {
			this.$nextTick(() => {
				// this.$refs.map.mapObject.setPrefix('');
				// this.$refs.map.mapObject.attributionControl.options.prefix = '';
				// console.log(this.$refs.map.mapObject);//.attributionControl.options);//.ANY_LEAFLET_MAP_METHOD();
			})
		}

	}
</script>
