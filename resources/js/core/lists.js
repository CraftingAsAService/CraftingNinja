/**
 * Core Lists
 */

caas.lists = {
	activeList: {},
	init:function() {
		// Require the game variable to be set
		if (typeof game === 'undefined')
			return;

		this.setup();
		this.events();
	},
	setup:function() {
		this.getActiveList();
	},
	events:function() {
		$(document).on('click', '.add-to-list', this.simpleAdd);
	},
	// Actions
	getActiveList:function() {
		var activeList = caas.cookie.get(game.slug + '-active-list');

		if (activeList === null)
			return;

		this.activeList = JSON.parse(atob(activeList));
	},
	saveActiveList:function() {
		// Base64 encode before saving in cookie to save some space, kill any ending ='s as well
		caas.cookie.set(game.slug + '-active-list', btoa(JSON.stringify(this.activeList)).replace(/=*$/, ''));
		this.updateButtons();

		// TODO - Save to database
	},
	simpleAdd:function() {
		var buttonEl = $(this),
			id = buttonEl.data('id'),
			type = buttonEl.data('type');

		caas.lists.update(id, type, '++');
		caas.lists.saveActiveList();

		$.notifyClose();

		$.notify({
			message: '<span style="white-space: nowrap;"><a href="/crafting/from-list" class="btn btn-primary mr-3"><i class="fas fa-magic"></i> Craft</a>' +
				'<a href="/knapsack" class="btn btn-primary"><i class="fas fa-shopping-cart"></i> List</a></span>'
		}, {
			element: 'body',
			type: 'info',
			animate: {
				enter: 'animated fadeInRight',
				exit: 'animated fadeOutRight'
			},
			delay: 3000,
			allow_dismiss: false,
			newest_on_top: true
		});
	},
	update:function(id, type, amount) {
		if (typeof id === 'undefined' || typeof type === 'undefined')
			return;

		if (typeof caas.lists.activeList[type] === 'undefined')
			caas.lists.activeList[type] = {};

		if (typeof caas.lists.activeList[type][id] === 'undefined')
			caas.lists.activeList[type][id] = 0;

		if (amount == '++')
			caas.lists.activeList[type][id]++;
		else
			caas.lists.activeList[type][id] = amount;

		caas.lists.saveActiveList();
	},
	delete:function(id, type) {
		if (typeof id === 'undefined' || typeof type === 'undefined')
			return;

		delete caas.lists.activeList[type][id];
		caas.lists.saveActiveList();
	},
	empty:function() {
		this.activeList = {};
		caas.lists.saveActiveList();
	},
	updateButtons:function() {
		// Loop through all add-to-list buttons, update based on existing quantities
		$('.add-to-list').each(function() {
			var buttonEl = $(this),
				id = buttonEl.data('id'),
				type = buttonEl.data('type'),
				valid = typeof caas.lists.activeList[type] !== 'undefined' && typeof caas.lists.activeList[type][id] !== 'undefined' && caas.lists.activeList[type][id] > 0;

			buttonEl.toggleClass('btn-light', ! valid).toggleClass('btn-primary', valid)
				.find('.badge').html(valid ? caas.lists.activeList[type][id] : '').toggleAttr('hidden', ! valid);
		});
	}
};
