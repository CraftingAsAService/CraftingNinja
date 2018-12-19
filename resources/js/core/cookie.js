/**
 * Core Cookie Handler
 */

caas.cookie = {
	set:function(name, val) {
		var expire = new Date();
		expire.setDate(expire.getDate() + 365);

		val = escape(val) + '; domain=' + cookieDomain + '; expires=' + expire.toUTCString() + '; path=/';

		document.cookie = name + '=' + val;

		return true;
	},
	get:function(name, defaultValue) {
		name += '=';
		var cookieArray = document.cookie.split(';');

		for (var x = 0; x < cookieArray.length; x++) {
			var cookie = cookieArray[x];
			while (cookie.charAt(0) == ' ')
				cookie = cookie.substring(1, cookie.length);

			if (cookie.indexOf(name) === 0)
				return cookie.substring(name.length, cookie.length);
		}

		return defaultValue || null;
	},
	delete:function(name) {
		document.cookie = name + '=; domain=' + cookieDomain + '; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
	}
};
