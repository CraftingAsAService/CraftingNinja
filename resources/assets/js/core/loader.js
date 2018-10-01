/**
 * Core Loader
 * 	Loads javascript and css files synchronously
 * 	Requires a global `assets` variable to exist
 * 	Automatically included into the core.js file
 */

caas.loader = {
	queue: [],
	callbackQueue: [],
	loaded: [],
	loading: false,
	init:function() {
		if (typeof assets === 'undefined')
			return;

		// CSS Files are always ASync loaded
		assets.filter(function(asset) {
			return asset.substr(-3) == 'css';
		}).forEach(function(cssAsset) {
			caas.loader.asyncUp(cssAsset);
		});

		// JS files added to `assets` are always queued to preserve order of operations
		assets.filter(function(asset) {
			return asset.substr(-2) == 'js';
		}).forEach(function(jsAsset) {
			caas.loader.queueUp(jsAsset, undefined, false);
		});

		this.run();
	},
	// Actions
	cdnize:function(src) {
		// If this is a relative URL, prepend the CDN
		//  Protect against protocol agnostic `//` urls.
		// if (src.charAt(0) == '/' && src.charAt(1) != '/')
		// 	src = caas.site.cdn + src;

		return src;
	},
	asyncUp:function(src) {
		// asyncUp is only for quick-loading, non-callback, optional requests
		// If you need callbacks, or any kind of order of operations loading, use queueUp instead

		src = this.cdnize(src);

		// Prevent double loads, skip over it
		if (this.loaded.indexOf(src) >= 0)
			return;

		// Mark the file as loaded
		this.loaded.push(src);

		// Load the file, indicating this is an async load
		this.appendFile(src, true);
	},
	queueUp:function(src, callback, run /* default: true */) {
		// If callback doesn't exist, give it a default blank function
		if (typeof callback !== 'function')
			callback = this.intentionallyBlank;

		src = this.cdnize(src);

		// Add both the file and the callback to separate queues.
		this.queue.push(src);
		this.callbackQueue.push(callback);

		// Assume we want to run
		if (typeof run === 'undefined')
			run = true;

		// If we didn't specifically disable running, run the queue
		if (run != false)
			this.run();
	},
	run:function() {
		if (this.queue.length == 0)
			return;

		// Prevent double runs
		if (this.loading)
			return;

		this.loading = true;

		// Get the next file in Queue, while also removing it
		var src = this.queue.shift();

		// Prevent double loads, skip over it
		if (this.loaded.indexOf(src) >= 0)
			return this.finished(src);

		// Mark the file as loaded
		this.loaded.push(src);

		// Load the file
		this.appendFile(src, false);
	},
	appendFile:function(src, async) {
		if (src.substr(-2) == 'js')
			return this.appendScript(src, async);
		else if (src.substr(-3) == 'css')
			return this.appendStyle(src);
	},
	appendStyle:function(src) {
		var styleEl = document.createElement('link');
		styleEl.rel = 'stylesheet';
		styleEl.href = src;
		document.head.appendChild(styleEl);
	},
	appendScript:function(src, async) {
		var scriptEl = document.createElement('script');
		scriptEl.src = src;
		if ( ! async)
			scriptEl.setAttribute('onload', 'caas.loader.finished()');
		document.body.appendChild(scriptEl);
	},
	finished:function() {
		// Get the next callback in Queue, while also removing it
		var callback = this.callbackQueue.shift();

		(callback)();

		// Now that it's finished, it's no longer loading
		this.loading = false;

		// Start loading the next script
		this.run();
	},
	intentionallyBlank:function() {
		return undefined;
	}
};
