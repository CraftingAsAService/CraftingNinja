/**
 * Core Functions
 *  Additional functionality through basic functions
 */

function ucfirst(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

$.fn.toggleAttr = function(attr, assert) {
	if (assert)
		$(this).attr(attr, attr);
	else
		$(this).removeAttr(attr);

	return this;
};

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
		'X-LOCALE': $('html').attr('lang'),
	}
});
