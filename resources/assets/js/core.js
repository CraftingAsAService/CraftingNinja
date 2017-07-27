/**
 * Core
 */

'use strict';

var cass = {};

caas.core = {
	modules: [
		'loader',
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
 * Extended Core
 */
@import 'core/loader.js';

$(function() { caas.core.init(); });