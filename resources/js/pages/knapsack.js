/**
 * Knapsack Page
 */

'use strict';

caas.knapsack = {
	init:function() {
		this.setup();
		this.events();
	},
	setup:function() {

	},
	events:function() {
		$('.knapsack [data-type] input').on('keyup keypress change blur', $.debounce(250, false, caas.knapsack.updateList));
		$('.knapsack .delete').on('click', this.deleteEntity);

		$(document).on('click', '#emptyList', this.emptyList);
		$('#publishList').on('click', this.revealPublishPanel);
		$('#publish').on('click', this.publish);
	},
	// Actions
	updateList:function(event) {
		var inputEl = $(this),
			amount = inputEl.val(),
			entityEl = inputEl.closest('.compendium-item'),
			entityType = entityEl.data('type'),
			entityId = entityEl.data('id'),
			listEl = inputEl.closest('.knapsack'),
			listElId = listEl.data('id'),
			savedIconEl = listEl.find('.saved-icon'),
			savedIconTimeout = savedIconEl.data('timeout');

		// Update the qty
		caas.lists.update(entityId, entityType, amount);

		// Toggle the saved icon
		savedIconEl.addClass('-active');
		if (typeof savedIconTimeout !== 'undefined')
			clearTimeout(savedIconTimeout);
		savedIconEl.data('timeout', setTimeout(function() {
			savedIconEl.removeClass('-active');
		}, 5000));
	},
	deleteEntity:function() {
		var el = $(this),
			entityEl = el.closest('.compendium-item'),
			entityType = entityEl.data('type'),
			entityId = entityEl.data('id');

		caas.lists.delete(entityId, entityType);
		entityEl.remove();
	},
	emptyList:function() {
		caas.lists.empty();
		window.location = '/knapsack';
	},
	revealPublishPanel:function() {
		$('#publish').toggleAttr('hidden', false);
	},
	publish:function() {

	}
};

$(function() { caas.core.initializePage('knapsack'); });
