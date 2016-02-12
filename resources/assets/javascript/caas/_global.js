/**
 * Global
 *  Named with an underscore because it needs to fire first
 *  Figure out some common items that need to be in place first
 */
var global = {
	elements: {
		window: null,
		document: null,
		body: null,
		html: null
	},
	// Browser and System lists are not comprehensive
	//  Only supported browsers are included
	// Browser Identifiers
	browsers: {
		msie: false,
		opera: false,
		chrome: false,
		safari: false,
		firefox: false,

		// Specific MSIE Browser Versions
		versions: {
			ie8: false,
			ie9: false,
			ie10: false,
			ie11: false,
			edge: false
		}
	},
	// System Identifiers
	systems: {
		// Traditional Desktop Systems
		windows: false,
		mac: false,
		linux: false,

		// New age systems
		ios: false,
		android: false,

		// General toggle for a handheld device
		handheld: false
	},
	init:function() {
		// Set some common elements
		global.elements.window = $(window);
		global.elements.document = $(document);
		global.elements.html = $('html');
		global.elements.body = $('body');

		// Browser Detection
		var appName = navigator.appName,
			userAgent = navigator.userAgent,
			browserMatch = userAgent.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i),
			tem = null;

		// If there's a browser match, update the version number
		if (browserMatch && (tem = userAgent.match(/version\/([\.\d]+)/i)) != null)
			browserMatch[2] = tem[1];

		// Setup browsermatch to contain what we want
		browserMatch = browserMatch ? [browserMatch[1], browserMatch[2]] : [appName, navigator.appVersion];

		var browser = browserMatch[0].toLowerCase(),
			version = browserMatch[1];

		// Internet Explorer tries to play games, but we can still sniff them out
		// !! switches the false answer from null to false
		if (!!userAgent.match(/Trident.*rv[ :]*11\./)) {
			browser = 'msie';
			version = 11;
		} else if (!!userAgent.match(/\bEdge\/\d+/i)) {
			browser = 'msie';
			version = 'edge';
		}

		// Add the browser
		global.elements.html.addClass(browser);
		global.browsers[browser] = true;

		// Add the msie version
		if (global.browsers.msie) {
			// Prepend 'ie' to the numbered browsers.  Edge is exempt from this.
			if (! isNaN(parseInt(version)))
				version = 'ie' + parseInt(version);

			global.elements.html.addClass(version);
			global.browsers.versions[version] = true;
		}

		// Operating System Detection
		var platform = navigator.platform,
			platformUppercase = platform.toUpperCase();

		// Test for Windows, Mac, Linux, IOS, Android
		if (platformUppercase.indexOf('WIN') >= 0)
			global.systems.windows = true;
		else if (platformUppercase.indexOf('MAC') >= 0)
			global.systems.mac = true;
		else if (platformUppercase.indexOf('LINUX') >= 0)
			global.systems.linux = true;
		else if (platform.match(/(iPhone|iPod|iPad|Pike)/i))
			global.systems.ios = true;
		else if (platform.match(/Android/i))
			global.systems.android = true;

		// Loop through global.systems, and add the true class to the html tag
		for (var key in global.systems)
			if (global.systems.hasOwnProperty(key))
				if (global.systems[key]) {
					global.elements.html.addClass(key);
					break;
				}

		// Handheld Detection
		global.systems.handheld = !!userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i);
		global.elements.html.addClass(global.systems.handheld ? 'is-handheld' : 'not-handheld');
	}
}

$(global.init);