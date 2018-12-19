/**
 * Core Tooltips
 */

caas.tooltip = {
	init:function() {
		$('[data-toggle=tooltip]').tooltip({
			'html': true,
			'container': 'body'
		});
	}
};
