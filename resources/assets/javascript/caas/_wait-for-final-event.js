// Imagine a mousemove event, where every pixel move would trigger a big complex function to run
// Avoid that by using this
// Thanks to http://stackoverflow.com/a/4541963/286467
var wait_for_final_event = (function () {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if ( ! ms)
			ms = 50;

		if ( ! uniqueId)
			uniqueId = "Don't call this twice without a uniqueId";

		if (timers[uniqueId])
			clearTimeout(timers[uniqueId]);

		timers[uniqueId] = setTimeout(callback, ms);
	};
})();