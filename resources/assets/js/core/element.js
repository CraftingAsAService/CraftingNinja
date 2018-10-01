/**
 * Core Elements
 */

caas.element = {
	init:function() {
		$(document).on('click', '[data-toggle=element]', this.toggle);
	},
	toggle:function() {
		var el = $(this);
		$(el.data('element')).toggleAttr('hidden', ! $(el.data('element'))[0].hasAttribute('hidden'));
	}
};
