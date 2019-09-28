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
				:icon-url='"/assets/" + gameSlug + "/map/icons/" + marker.icon + ".png"'
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
		name: 'ninjamap',
		props: [ 'mapName', 'markers' ],
		components: {
			LMap, LImageOverlay, LControlAttribution, LControlZoom, LMarker, LPopup, LIcon
		},
		data() {
			return {
				map: null,
				gameSlug: game.slug,
				mapImage: '/assets/' + game.slug + '/m/r2f1/r2f1.00.jpg',
				mapOptions: {
					zoomControl: false,
					attributionControl: false,
					zoomSnap: true,
					popperOptions: {
						position: 'top'
					}
				},
				mapWidth: size,
				bounds: [[-size, 0], [0, size]],
				minZoom: 0,
				maxZoom: 3,
				crs: CRS.Simple,
				noWrap: true,
				zoomPosition: 'topleft',
				attribution: '',
				attributionPosition: 'bottomright',
				attributionPrefix: this.mapName,
				coordinateOutput: this.styleCoordinates(),
				mapSizeModifier: null
			}
		},
		beforeCreate() {

		},
		created() {
			this.setModifier();
		},
		mounted() {
			this.$nextTick(() => {
				this.map = this.$refs.map.mapObject;

				this.map.on('mousemove', (event) => {
					var modifier = this.mapSizeModifier,
						xy = this.map.project(event.latlng, 1),
						xo = xy['x'],
						yo = xy['y'],
						xn = Number(((xo / modifier) + 1).toFixed(1)),
						yn = Number(((yo / modifier) + 1).toFixed(1));

					if (parseInt(xn) === xn)
						xn = xn + ".0";
					if (parseInt(yn) === yn)
						yn = yn + ".0";

					this.coordinateOutput = this.styleCoordinates(xn, yn);
				});
				// TODO TURN THIS ON, DISABLED FOR DEBUGGING
				// this.map.on('contextmenu', (event) => {
				// 	return false;
				// });
				this.map.on('mouseout', (event) => {
					this.coordinateOutput = this.styleCoordinates();
				});
			})
		},
		methods: {
			styleCoordinates:function(x, y) {
				return '<span class="text-muted">&lt;</span>' + (x || '0.0') + ', ' + (y || '0.0') + '<span class="text-muted">&gt;</span>'
			},
			convertCoordinates:function(x, y) {
				return {
					lat: (y / 2) * -this.mapSizeModifier,
					lng: (x / 2) * this.mapSizeModifier
				};
			},
			setModifier:function() {
				// TODO different maps have different ratios
				this.mapSizeModifier = this.mapWidth / 21.5;
			}
		}
	}
</script>
