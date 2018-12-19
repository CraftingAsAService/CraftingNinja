/**
 * Core
 *  The core of our javascript application/framework
 *  Placing everything within the global `caas` variable prevents third party scripts from touching/overwriting our own
 */

'use strict';

// `core` is guaranteed to load first of any module;
//   there's no need to redefine it in any other module, ala `var caas = caas || {};`
var caas = {};

caas.core = {
	// Core Modules will never have preloads
	// Things are loaded in this order, which covers any prerequisite code
	modules: [
		'loader',
		'popover',
		'tooltip',
		'element',
		'lists'
	],
	init:function() {
		// Core JS Functionality
		this.initializeCoreModules();
	},
	// Actions
	initializeCoreModules:function() {
		for (var i = 0; i < this.modules.length; i++)
			caas[this.modules[i]].init();
	},
	initializePage:function(page, baseObj) {
		// initializePage is just an alias for initializeModule
		this.initializeModule(page, baseObj);
	},
	initializeModule:function(module, baseObj) {
		// Setting a different base object allows for a sub-module to be initialized
		if (typeof baseObj === 'undefined')
			baseObj = caas;

		// Make sure the module exists
		if (typeof baseObj[module] !== 'object') {
			console.log('Attempting to load invalid module:', module);
			return;
		}

		var moduleObj = baseObj[module];

		// Check for files to asynchronously load
		if (typeof moduleObj.load === 'object' && moduleObj.load.length > 0)
			for (var i = 0; i < moduleObj.load.length; i++)
				caas.loader.asyncUp(moduleObj.load[i]);

		// If there aren't any preloads, just initialize the module and exit this function
		if (typeof moduleObj.preload !== 'object' || moduleObj.preload.length == 0)
			return moduleObj.init();

		// We've established that there are preloads.
		// Make sure they're all loaded before initializing
		moduleObj.preloadLoaded = 0;
		for (var i = 0; i < moduleObj.preload.length; i++) {
			caas.loader.queueUp(moduleObj.preload[i], function() {
				moduleObj.preloadLoaded++;
				if (moduleObj.preloadLoaded == moduleObj.preload.length)
					moduleObj.init();
			});
		}
	}
};

/**
 * Extending Core
 * 	Gulp will grab these files from the core folder and compile them as if they were inside this file itself
 * 	They don't need the 'use strict';, as it would double up the definition inside this file
 */

@import '../../node_modules/axios/dist/axios.min.js';
@import '../../node_modules/vue/dist/vue.min.js';
// http://bootstrap-notify.remabledesigns.com/
@import '../../node_modules/bootstrap4-notify/bootstrap-notify.js';

@import 'core/plugins/_functions.js';
@import 'core/plugins/debounce.js';
@import 'core/cookie.js';
@import 'core/element.js';
@import 'core/lists.js';
@import 'core/loader.js';
@import 'core/popover.js';
@import 'core/storage.js';
@import 'core/tooltip.js';

// Not using shorthand here as it screws up the `this` variable inside `init()`
$(function() { caas.core.init(); });
