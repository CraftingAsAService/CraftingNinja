<template>
	<l-map
		style='background-color: #392d49; border-radius: 4px; border: 1px solid #4b3b60;'
		ref='map'
		:options='mapOptions'
		:min-zoom='minZoom'
		:max-zoom='maxZoom'
		:crs='crs'
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
			position='bottomleft'
			:prefix='attributionPrefix'
		/>
		<l-control-attribution
			position='bottomright'
			prefix='<span class="text-muted">&lt;</span>0.0, 0.0<span class="text-muted">&gt;</span>'
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
				map: null,
				mapImage: '/assets/' + game.slug + '/m/r2f1/r2f1.00.jpg',
				mapOptions: {
					zoomControl: false,
					attributionControl: false,
					zoomSnap: true
				},
				bounds: [[-this.size, 0], [0, this.size]],
				minZoom: 0,
				maxZoom: 3,
				crs: CRS.Simple,
				noWrap: true,
				zoomPosition: 'topleft',
				attribution: '',
				attributionPosition: 'bottomright',
				attributionPrefix: this.mapName
			}
		},
		mounted () {
			this.$nextTick(() => {
				this.map = this.$refs.map.mapObject;

				this.map.on('mousemove', (event) => {
					var modifier = this.size / 21.5,
						xy = this.map.project(event.latlng, 1),
						xo = xy['x'],
						yo = xy['y'],
						xn = Number(((xo / modifier) + 1).toFixed(1)),
						yn = Number(((yo / modifier) + 1).toFixed(1));

					if (parseInt(xn) === xn)
						xn = xn + ".0";
					if (parseInt(yn) === yn)
						yn = yn + ".0";

					this.map.attributionControl.getContainer().innerHTML = '<span class="text-muted">&lt;</span>' + xn + ', ' + yn + '<span class="text-muted">&gt;</span>';
				});
				// TODO TURN THIS ON, DISABLED FOR DEBUGGING
				// this.map.on('contextmenu', (event) => {
				// 	return false;
				// });
				this.map.on('mouseout', (event) => {
					this.map.attributionControl.getContainer().innerHTML = '<span class="text-muted">&lt;</span>0.0, 0.0<span class="text-muted">&gt;</span>';
				});
			})
		}

	}
</script>
