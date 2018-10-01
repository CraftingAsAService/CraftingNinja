/**
 * Core Storage Functionality
 */

caas.storage = {
	set:function(name, value) {
		localStorage.setItem(name, value);
	},
	get:function(name, defaultValue) {
		var value = localStorage.getItem(name);
		if (value === null && typeof defaultValue !== 'undefined')
			return defaultValue;
		return value;
	},
	remove:function(name) {
		localStorage.removeItem(name);
	},
	clear:function() {
		localStorage.clear();
	}
};
