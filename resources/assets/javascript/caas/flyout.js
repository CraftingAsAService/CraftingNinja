var flyout = {
	elements: {
		navs: null,
		overlay: null
	},
	config: {
		panels: {},
		open_queue: [],
		z_index: 11,
		width: 320,
		duration: 250
	},
	scotchPanels: null,
	init:function() {
		flyout.setup();
		flyout.events();
	},
	setup:function() {
		flyout.elements.navs = $('#primary-wrapper > .flyout-box');
		// Make all the flyout boxes a scotchPanel, adding them to a list in the process
		flyout.elements.navs.each(flyout.actions.setup_flyout_box);

		flyout.elements.overlay = $('.overlay');
	},
	events:function() {
		// Clicking on the overlay closes everything
		flyout.elements.overlay.click(flyout.actions.close_panels);

		// Open/Close buttons
		global.elements.document.on('click', '.open-menu', flyout.actions.open_menu);
		global.elements.document.on('click', '.close-menu', flyout.actions.close_menu);

		// Escape Key to Close Menu
		global.elements.document.keyup(flyout.actions.escape_key_close); 
	},
	actions: {
		setup_flyout_box:function() {
			var el = $(this),
				id = el.attr('id');

			flyout.config.panels[id] = el.scotchPanel({
				forceMinHeight: true,
				// enableEscapeKey: true, // We require a custom one
				containerSelector: '#primary-wrapper',
				duration: flyout.config.duration,
				distanceX: flyout.config.width + 'px',
				element: $('#' + id),
				// http://codepen.io/ncerminara/pen/ByBYqx
				beforePanelOpen:function() {
					flyout.elements.navs.css('z-index', 0);
					this.element.css('z-index', ++flyout.config.z_index);

					// Check if it has to load ajax content
					flyout.actions.load_menu_content($('#' + id));

					// Alter the floating header's position

					// We're only targetting flyout boxes that are direct children of the primary wrapper
					//  so the Y axis won't be used here
					var x = 0;

					if (this.direction == 'left') x = flyout.config.width + 'px';
					else if (this.direction == 'right') x = '-' + flyout.config.width + 'px';

					// Move the overlay too
					$('.floating-header, .overlay').css('transform', 'translate3d(' + x + ', 0, 0)');

					// Show the overlay
					flyout.elements.overlay.addClass('-show');
				},
				beforePanelClose:function() {
					// Reset the header and overlay's position
					$('.floating-header, .overlay').css('transform', 'translate3d(0, 0, 0)');

					// Hide the overlay
					flyout.elements.overlay.removeClass('-show');
				}
			});
		},
		escape_key_close:function(event) {
			if (event.keyCode == 27)
				flyout.actions.close_panels();
		},
		close_panels:function(event) {
			if (typeof event !== 'undefined')
				event.preventDefault();

			if (flyout.config.open_queue.length == 0)
				return;
			
			// Reset the open_queue
			flyout.config.open_queue = [];

			// Close all the panels
			$.each(flyout.config.panels, function() {
				this.close();
			});
		},
		close_menu:function(event) {
			event.preventDefault();
			
			var el = $(this),
				target_el = el.closest('.flyout-box'),
				id = target_el.attr('id'),
				scotchPanel = flyout.config.panels[id];

			// Remove this panel from the open queue
			// Using lastIndexOf because it can show up multiple times, we just want to drop the last occurence
			var index = flyout.config.open_queue.lastIndexOf(id);
			if (index > -1)
				flyout.config.open_queue.splice(index, 1);

			// Depending on if any flyouts are still open, do different things
			var still_open = flyout.config.open_queue.length || 0;
			if (still_open == 0) {
				// No options, close the whole scotchPanel
				scotchPanel.close();
			} else {
				// Get the last one that's open
				var new_target = flyout.config.open_queue[still_open - 1];

				// Reopen this one
				flyout.actions.reopen_menu(target_el, new_target);
			}
		},
		open_menu:function(event) {
			event.preventDefault();
			
			var el = $(this),
				target_el = $(el.data('target')),
				id = target_el.attr('id'),
				scotchPanel = flyout.config.panels[id];

			if (typeof scotchPanel == 'undefined')
				return;

			// Make sure the target is visible
			target_el.removeClass('invisible');

			// Depending on if any flyouts are already open, do different things
			var already_open = flyout.config.open_queue.length || 0;
			if (already_open == 0) {
				// No options, open the whole scotchPanel
				scotchPanel.open();
			} else {
				// Check if it has to load ajax content
				flyout.actions.load_menu_content(target_el);

				// Reopen this one
				flyout.actions.reopen_menu(target_el, id);
			}

			// Move the top of the menu to where the window is
			flyout.actions.scrolldown_menu(target_el);

			// Wait the animation timeout, then make all other navs invisible
			setTimeout(function() {
				flyout.elements.navs.not(target_el).addClass('invisible');
			}, flyout.config.duration);

			// Add this panel to the open queue
			flyout.config.open_queue.push(id);
		},
		reopen_menu:function(target_el, new_target) {
			var new_target_el = $('#' + new_target),
				new_target_scotchPanel = flyout.config.panels[new_target],
				scotchPanel = new_target_scotchPanel;

			if (typeof scotchPanel == 'undefined')
				return;

			// Make sure the target is visible
			new_target_el.removeClass('invisible');

			var direction = scotchPanel.settings.direction;

			// We don't want to use the scotchPanel open, because it's already technically open
			// flyout.config.panels[target].open();
			// Instead, we have to pretend.  Move that menu double what it's direction is (right -320?  right -640), 
			//   and set it's z-index to one higher (++flyout.config.z_index)
			// Make sure the current element is also the highest one, instead of resetting to zero
			// Then, animate it back in.

			// Set the current element's z-index to the highest one (just to make sure)
			target_el.css('z-index', ++flyout.config.z_index);

			// Double direction and increase z-index of new target
			new_target_el
				.css('z-index', ++flyout.config.z_index)
				.css(direction, '-' + (flyout.config.width * 2) + 'px');

			new_target_el.animate(
				// Returns an object: { left: -320px }
				(function(o) { o[direction] = '-' + flyout.config.width + 'px'; return o; })({})
			, flyout.config.duration);

			// Move the top of the menu to where the window is
			flyout.actions.scrolldown_menu(new_target_el);
		},
		scrolldown_menu:function(el) {
			var nav_content_height = el.find('.wrapper').height(),
				window_available_height = global.elements.window.height(),
				window_scrolled_amount = global.elements.window.scrollTop(),
				document_total_height = global.elements.document.height();

			// Calculate what's left to scroll
			var scroll_height_remaining = document_total_height - window_scrolled_amount,
				scroll_to = window_scrolled_amount;

			// If we don't have the space left to scroll to, move it the difference
			if (scroll_height_remaining < nav_content_height)
			{
				scroll_to -= (nav_content_height - scroll_height_remaining) + 20;
				$('html, body').animate({ scrollTop: scroll_to + 'px' }, 0);
			}

			el
				.css('top', scroll_to + 'px')
				.css('min-height', window_available_height + 'px');
		},
		load_menu_content:function(el) {
			var content_identifier = el.data('content'),
				is_loading = el.hasClass('-loading');

			if (typeof content_identifier == 'undefined')
				return;

			var loading_el = $('#flyout-loading-template').clone(true, true);

			loading_el.attr('id', '');

			loading_el.appendTo(el);

			$.ajax({
				url: '/flyouts/' + content_identifier
			}).done(function(data) {
				el.html(data);
			}).fail(function() {
				el.html('<p>This panel was eaten by a Grue. Seek assistance!</p>');
			}).always(function() {
				el.removeClass('-loading');
			});
		}
	}
}

$(flyout.init);