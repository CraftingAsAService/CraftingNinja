'use strict';

window.Vue = require('vue');

require('./directives/ClickOutside.js');
require('./directives/Tooltip.js');
require('./directives/Axios.js');

Vue.prototype.$eventBus = new Vue(); // Global Event Bus

import NinjaCart from './components/NinjaCart'

const app = new Vue({
	el: '#app',
	created:function() {
		console.log('App!');
	},
	components: {
		NinjaCart
	}
})
