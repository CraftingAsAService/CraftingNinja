var common = {
	config: {
		faux_scrolled: 0 // When we want to trick it into thinking we're in a scrolled state
	},
	init:function() {
		common.setup();
		common.events();
	},
	setup:function() {
		common.actions.toggle_scrolled();
	},
	events:function() {
		// Add a scrolled class if they've scrolled any amount
		global.elements.window.on('scroll', function() {
			wait_for_final_event(common.actions.toggle_scrolled, false, 'detect_scroll');
		});
	},
	actions: {
		toggle_scrolled:function() {
			var is_scrolled = common.config.faux_scrolled > 0 || global.elements.window.scrollTop() > 0;

			global.elements.body.toggleClass('scrolled', is_scrolled);
		}
	}
}

$(common.init);