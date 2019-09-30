<template>
	<l-map
		style='background-color: #392d49; border-radius: 4px; border: 1px solid #4b3b60;'
		ref='map'
		:options='leaflet.options'
		:min-zoom='leaflet.minZoom'
		:max-zoom='leaflet.maxZoom'
		:crs='leaflet.crs'
		:noWrap='leaflet.noWrap'
		:bounds='leaflet.bounds'
		:max-bounds='leaflet.bounds'
	>
		<l-image-overlay
			:url='this.mapSrc'
			:bounds='leaflet.bounds'
			attribution=''
		/>

		<l-control-zoom position='topleft' />
		<l-control-attribution
			position='bottomleft'
			:prefix='this.mapName'
		/>
		<l-control-attribution
			position='bottomright'
			:prefix='coordinateOutput'
		/>

		<l-marker
			v-for='marker in this.markers'
			:key='marker.id'
			:lat-lng='convertCoordinates(marker.x, marker.y)'
		>
			<l-icon
				:icon-size='[24, 24]'
				:icon-anchor='[24 / 2, 0]'
				:icon-url='marker.icon'
			/>
			<l-popup
				:content='marker.tooltip'
				/>
		</l-marker>
	</l-map>
</template>

<script>
	import { LMap, LImageOverlay, LControlAttribution, LControlZoom, LMarker, LPopup, LIcon } from 'vue2-leaflet'
	import { CRS, Icon } from 'leaflet'
	import 'leaflet/dist/leaflet.css'

	let size = 577; // TODO calculate magic number based on column width

	export default {
		props: [ 'mapName', 'mapSrc', 'mapBounds', 'markers' ],
		components: {
			LMap, LImageOverlay, LControlAttribution, LControlZoom, LMarker, LPopup, LIcon
		},
		data() {
			return {
				map: null,
				leaflet: {
					minZoom: 0,
					maxZoom: 3,
					crs: CRS.Simple,
					noWrap: true,
					bounds: null,
					options: {
						zoomControl: false,
						attributionControl: false,
						zoomSnap: true,
						popperOptions: {
							position: 'top'
						}
					}
				},
				gameSlug: game.slug,
				coordinateOutput: '',
				mapWidth: null,
				mapSizeModifier: null
			}
		},
		created() {
			this.setupNewMap();
		},
		mounted() {
			this.$nextTick(() => {
				this.map = this.$refs.map.mapObject;

				this.map.on('mousemove', (event) => {
					var xy = this.map.project(event.latlng, 1),
						xCoord = ((xy['x'] / this.mapSizeModifier) + 1).toFixed(1),
						yCoord = ((xy['y'] / this.mapSizeModifier) + 1).toFixed(1);

					this.coordinateOutput = this.styleCoordinates(xCoord, yCoord);
				});

				// TODO TURN THIS ON, DISABLED FOR DEBUGGING
				// this.map.on('contextmenu', (event) => {
				// 	return false;
				// });

				this.map.on('mouseout', (event) => {
					this.coordinateOutput = '';//this.styleCoordinates();
				});
			})
		},
		methods: {
			setupNewMap:function() {
				this.mapWidth = document.getElementById('mapContainer').clientWidth;
				this.leaflet.bounds = [[-this.mapWidth, 0], [0, this.mapWidth]];
				let coordinateRange = this.mapBounds[1][0] - this.mapBounds[0][0];
				this.mapSizeModifier = this.mapWidth / coordinateRange * 2;
			},
			styleCoordinates:function(x, y) {
				return '<span class="text-muted">&lt;</span>' + (x || '0.0') + ', ' + (y || '0.0') + '<span class="text-muted">&gt;</span>'
			},
			convertCoordinates:function(x, y) {
				return {
					lat: (y / 2) * -this.mapSizeModifier,
					lng: (x / 2) * this.mapSizeModifier
				};
			}
		}
	}
</script>
