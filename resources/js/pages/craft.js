/**
 * Craft Page
 */

'use strict';

import { LMap, LImageOverlay, LMarker } from 'vue2-leaflet'
import { CRS, Icon } from 'leaflet'
import 'leaflet/dist/leaflet.css'

// Map is presented as a square
var size = 577;

const compendium = new Vue({
	el: '#craft',
	components: { LMap, LImageOverlay, LMarker },
	data() {
		return {
			url: '/assets/' + game.slug + '/m/r2f1/r2f1.00.jpg',
			bounds: [[0, 0], [size, size]],
			center: [size / 2, size / 2],
			minZoom: 0,
			maxZoom: 3,
			crs: CRS.Simple,
			noWrap: true
		}
	}
});
