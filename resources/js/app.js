'use strict';

window.Vue = require('vue');
Vue.use(require('vue-cookies'));

require('./directives/ClickOutside.js');
require('./directives/Tooltip.js');
require('./directives/Axios.js');

Vue.prototype.$eventBus = new Vue(); // Global Event Bus

Vue.component('ninja-cart', require('./components/NinjaCart.vue').default);

const header = new Vue({
	el: '#header'
})
