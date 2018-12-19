/**
 * Core Popovers
 */

caas.popover = {
	force: false,
	init:function() {
		this.setup();
		this.events();
	},
	setup:function() {
		$('[data-toggle=popover]').popover({
			'html': true,
			'container': 'body'
		});
	},
	events:function() {
		// only allow 1 popover at a time
		$('body').on('show.bs.popover', this.thereCanBeOnlyOne);

		// Click outside of a popover to hide it
		$('body').on('click', this.hideOnOutsideClick);
	},
	// Actions
	closeAll:function() {
		$('[data-toggle=popover]').popover('hide');
	},
	thereCanBeOnlyOne:function(event) {
		$('[data-toggle=popover]').not($(event.target)).popover('hide');
	},
	hideOnOutsideClick:function(event) {
		if ( ! caas.popover.force && $('.popover:visible').length > 0 && $(event.target).closest('[data-original-title]').length === 0 && ! $(event.target).closest('.popover').is('.show'))
			$('[aria-describedby="' + $('.popover.show').first().attr('id') + '"]').popover('hide');

		caas.popover.force = false;
	}
};
