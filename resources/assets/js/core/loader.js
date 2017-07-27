/**
 * Core Loader
 * 	Loads javascript files synchronously
 * 	Requires a global `scripts` variable to exist
 * 	Automatically included into the core.js file
 */

caas.loader = {
	queue: [],
	callbackQueue: [],
	loaded: [],
	loading: false,
	init:function() {
		if (typeof scripts == 'undefined')
			return;

		this.massQueue(scripts);
	},
	// Actions
	asyncUp:function(src) {
		// asyncUp is only for quick-loading, non-callback, optional requests
		// If you need callbacks, or any kind of order of operations loading, use queueUp instead

		// Prevent double loads, skip over it
		if (this.loaded.indexOf(src) >= 0)
			return;

		// Mark the file as loaded
		this.loaded.push(src);

		// Load the file, indicating this is an async load
		this.appendScript(src, true);
	},
	queueUp:function(src, callback, run /* default: true */) {
		// If callback doesn't exist, give it a default blank function
		if (typeof callback !== 'function')
			callback = this.intentionallyBlank;

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
	massQueue:function(srcs) {
		// srcs is expected to be an array `[]` with at least one entry
		if (typeof srcs !== 'object' || srcs.length == 0)
			return;

		// Add each file to the queue, but don't run just yet
		for (var i = 0; i < srcs.length; i++)
			this.queueUp(srcs[i], undefined, false);

		// Now we can run
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
		this.appendScript(src, false);
	},
	appendScript:function(src, async) {
		var scriptEl = document.createElement('script');
		scriptEl.src = src;
		if ( ! async)
			scriptEl.setAttribute('onload', 'caas.loader.finished("' + src + '")');
		document.body.appendChild(scriptEl);
	},
	finished:function(src) {
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