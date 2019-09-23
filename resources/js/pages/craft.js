/**
 * Craft Page
 */

'use strict';

import { LMap, LImageOverlay, LMarker } from 'vue2-leaflet'
import { CRS, Icon } from 'leaflet'
import 'leaflet/dist/leaflet.css'

// var map = L.map('map', {
// 	crs: L.CRS.Simple,
// 	minZoom: -5
// });
// var bounds = [[0,0], [1000,1000]];
// var image = L.imageOverlay('uqm_map_full.png', bounds).addTo(map);
// map.fitBounds(bounds);

// delete Icon.Default.prototype._getIconUrl;

// Icon.Default.mergeOptions({
// 	iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
// 	iconUrl: require('leaflet/dist/images/marker-icon.png'),
// 	shadowUrl: require('leaflet/dist/images/marker-shadow.png')
// });

const compendium = new Vue({
	el: '#craft',
	components: { LMap, LImageOverlay, LMarker },
	data() {
		return {
			url: '/assets/' + game.slug + '/m/r2f1/r2f1.00.jpg',
			bounds: [[0, 0], [578, 578]],
			center: [289, 289],
			minZoom: 0,
			maxZoom: 3,
			crs: CRS.Simple,
			noWrap: true
		}
	}
});
