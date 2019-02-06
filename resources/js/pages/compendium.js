/**
 * Compendium Page
 */

'use strict';

var compendium = new Vue({
	el: '#compendium',
	data: {
		searchTerm: typeof searchTerm !== 'undefined' ? searchTerm : '',
		filters: {},
		chapter: 'items',
		activeFilters: [],
		noResults: true,
		results: [],
	},
	mounted: function() {
		this.buildRanges();
		if (this.searchTerm)
			this.search();
	},
	methods: {
		search:function() {
			var call = 'items';

			if (chapters == 'quests')
				call = 'quests';

			var data = {
				// 'sorting': '',
				// 'ordering': '',
				'name': this.searchTerm,
			};

			// TODO, this should happen when they click the Checkbox
			// Search should just rely on what's in this.filters
			$('.widget_filter:visible').each(function() {
				var el = $(this),
					key = el.data('key'),
					type = el.data('type');

				if (type == 'range') {
					var sliderEl = el.find('.slider-range'),
						keys = sliderEl.data('keys').split(','),
						min = parseInt(sliderEl.find('.min').html()),
						max = parseInt(sliderEl.find('.max').html());
					data[keys[0]] = min;
					data[keys[1]] = max;
				}
			})

			axios
				.post('/api/' + call, data)
				.then(response => {
					this.results = response.data;
					this.noResults = response.data.length == 0;
				})
				.catch(error => console.log(error));
		},
		buildRanges:function() {
			$('.slider-range').each(function() {
				var el = $(this),
					domEl = el[0],
					min = parseInt(el.data('min')),
					max = parseInt(el.data('max')),
					snapEls = [
						el.parent().find('.min'),
						el.parent().find('.max'),
					];

				noUiSlider.create(domEl, {
					start: [ min, max ],
					connect: true,
					step: 1,
					range: {
						'min': [ min ],
						'max': [ max ]
					}
				});

				domEl.noUiSlider.on('update', function(values, key) {
					snapEls[key].html(values[key].replace('.00', ''));
				});
			});
		}
	}
});


	// el: '.my-lists',
	// data: function() {
	// 	return {
	// 		results: [],
	// 		noResults: false
	// 	}
	// },
	// mounted: function() {
	// 	axios
	// 		.get('/api/users/my/lists')
	// 		.then(response => {
	// 			this.results = response.data;
	// 			this.noResults = response.data.length == 0;
	// 		})
	// 		.catch(error => console.log(error));
	// },
	// methods: {

	// }

let defaultFilters = {
	chapter: 'items',
	sorting: 'name',
	ordering: 'asc',
	active: [],
};

var OLDcompendium = {
	filters: Object.assign({}, defaultFilters),
	results: null,
	pause: false,
	init:function() {
		this.setup();
		this.events();
	},
	setup:function() {
		this.restoreFilters();
	},
	events:function() {
		$('#chapter-select .dropdown-item').on('click', this.chapterSelect);

		$('a.page-link').on('click', this.paginate);

		$('input[name=name]')
			.on('keyup keypress focus blur change', this.searchWidth)
			.on('keyup keypress change blur', $.debounce(250, false, caas.compendium.search));

		$('#name-filter .dropdown-item').on('click', this.sorting);

		$('#select-filters .dropdown-item').on('click', this.createFilter);
		$('body')
			.on('click', this.closeFilter)
			.on('click', '.filter', this.activateFilter)
			.on('click', '.filter .delete', this.destroyFilter);

		$('.large-view').on('click', this.switchView);
	},
	// Actions
	chapterSelect:function(event) {
		if (typeof event !== 'undefined')
			event.preventDefault();

		var itemEl = $(this),
			chapter = itemEl.data('chapter');

		// Highlighting
		itemEl.siblings().removeClass('active');
		itemEl.addClass('active');

		// Update chapter name
		$('#chapter').html(itemEl.text());

		// Change what shows up in the filters menu
		$('#filters [data-chapter]').each(function() {
			var filterEl = $(this),
				applicableChapters = filterEl.data('chapter');

			filterEl.toggleAttr('hidden', applicableChapters.indexOf(chapter) === -1);
		});

		// Set the chapter
		caas.compendium.filters['chapter'] = chapter;

		// Update the results class
		$('#results').attr('data-chapter', chapter);

		// Search anew
		caas.compendium.getResults();
	},
	switchView:function(event) {
		if (typeof event !== 'undefined')
			event.preventDefault();

		var isLarge = $('#results').hasClass('-large-view');

		$('#results').toggleClass('-large-view', ! isLarge);
		$('.large-view .-disable').toggleAttr('hidden', isLarge);
		$('.large-view .-enable').toggleAttr('hidden', ! isLarge);
	},
	activateFilter:function(event) {
		var filterEl = $(this);

		// Stop if this filter is already active
		if (filterEl.hasClass('-active'))
			return;

		if (typeof event !== 'undefined')
			event.preventDefault();

		// Only this filter should be active
		$('#filtration .filter').removeClass('-active').css('left', 'inherit');
		filterEl.addClass('-active');
		setTimeout(function() {
			var delEl = filterEl.find('.delete'),
				// 16 for good measure
				offset = delEl.offset().left + delEl.outerWidth() + 16 - $(window).width();
			if (offset > 0)
				filterEl.css('left', '-' + offset + 'px');
		}, 250); // Matches 250ms transition on label, input, .delete, etc

		// If nothing is focused, focus the first
		if (filterEl.find('input:focus').length == 0)
			filterEl.find('input:visible').first().focus();
	},
	closeFilter:function(event) {
		if (typeof event !== 'undefined') {
			var targetEl = $(event.target);

			if (targetEl.closest('.filter').length > 0 || $('#filtration .filter.-active').length == 0)
				return;
		}

		var filterEl = $('#filtration .filter.-active').first(),
			filter = filterEl.data('filter'),
			type = filterEl.data('type'),
			wasKeep = filterEl.hasClass('-keep');

		// Don't de-activate it if we want to keep it
		filterEl.removeClass(wasKeep ? '-keep' : '-active');
		if ( ! wasKeep)
			filterEl.css('left', 'inherit');

		// Update active filters
		if (type == 'enabled') {
			caas.compendium.filters[filter] = 'true';
		} else if (type == 'single') {
			caas.compendium.filters[filter] = filterEl.find('input[name="' + filter + '"]').val();
		} else if (type == 'range') {
			var keys = filterEl.data('keys');
			caas.compendium.filters[keys[0]] = filterEl.find('input[name="' + keys[0] + '"]').val();
			caas.compendium.filters[keys[1]] = filterEl.find('input[name="' + keys[1] + '"]').val();
		} else {
			// Special cases are checkboxes[]
			var key = filterEl.find('input').attr('name').replace(/\[\]$/, ''),
				values = [];

			filterEl.find('input:checked').each(function() {
				values.push($(this).val());
			});

			caas.compendium.filters[key] = values.join(',');

			// Fix the displayed "values"
			var valuesEl = filterEl.find('.values');
			if (caas.compendium.filters[key].length == '') {
				valuesEl.find('img, i, .multiple').toggleAttr('hidden', true);
				valuesEl.find('.waiting-icon').toggleAttr('hidden', false);
			} else {
				var isMultiple = filterEl.find('input:checked').length > 1,
					firstEl = filterEl.find('input:checked').first().next('label').children().first();

				valuesEl.find('img, i').toggleAttr('hidden', false);

				if (firstEl.is('i'))
					valuesEl.find('i').first().attr('class', firstEl.attr('class'));
				else if (firstEl.is('img'))
					valuesEl.find('img').first().attr('src', firstEl.attr('src'));

				valuesEl.find('.multiple').toggleAttr('hidden', ! isMultiple);
				valuesEl.find('.waiting-icon').toggleAttr('hidden', true);
			}
		}

		// Update results
		caas.compendium.getResults();
	},
	destroyFilter:function(event) {
		var el = $(this),
			filterEl = el.closest('.filter'),
			filter = filterEl.data('filter'),
			type = filterEl.data('type');

		// Filters can be destroyed if pause mode is active
		if ( ! filterEl.is('.-active') && ! caas.compendium.pause)
			return;

		// Update active filters
		if (type == 'enabled' || type == 'single')
			delete caas.compendium.filters[filter];
		else if (type == 'range') {
			var keys = filterEl.data('keys');
			delete caas.compendium.filters[keys[0]];
			delete caas.compendium.filters[keys[1]];
		} else {
			// Special cases are checkboxes[]
			var key = filterEl.find('input').attr('name').replace(/\[\]$/, '');
			delete caas.compendium.filters[key];
		}

		var indexOfActiveFilter = caas.compendium.filters.active.indexOf(filter);
		if (indexOfActiveFilter > -1)
			caas.compendium.filters.active.splice(indexOfActiveFilter);

		// Remove the filter
		filterEl.remove();

		// Re-enable the filter select option
		$('#select-filters .dropdown-item[data-filter="' + filter + '"]').removeClass('disabled');

		// Update results
		caas.compendium.getResults();
	},
	createFilter:function(event) {
		if (typeof event !== 'undefined')
			event.preventDefault();

		var selectedEl = $(this);

		if (selectedEl.hasClass('disabled'))
			return;

		var filter = selectedEl.data('filter');

		if (filter == 'clear')
			return caas.compendium.clearFilters();

		var type = selectedEl.data('type'),
			text = selectedEl.data('text') || selectedEl.text(),
			icon = selectedEl.find('i').attr('class'),
			filterEl = $('#filters .filter.-' + type).clone();

		// The filter has now been added, disable it
		selectedEl.addClass('disabled');

		filterEl.find('.filter-icon').addClass(icon);
		filterEl.find('.filter-label').html(text);
		filterEl.data('filter', filter).data('type', type);

		if (type == 'enabled') {
			filterEl.find('input').attr('name', filter);
		} else if (type == 'single') {
			var inputEl = filterEl.find('input');
			inputEl
				.attr('name', filter)
				.attr('min', selectedEl.data('min'))
				.attr('max', selectedEl.data('max'));

			var listData = selectedEl.data('list');

			if (typeof listData !== 'undefined') {
				var datalistEl = $('<datalist id="' + filter + 'List"></datalist>');

				inputEl.attr('list', filter + 'List');
				$.each(listData.split(','), function() {
					datalistEl.append($('<option value="' + this + '"></option>'));
				});

				datalistEl.insertAfter(inputEl);
			}
		} else if (type == 'range') {
			var inputEls = filterEl.find('input'),
				keys = selectedEl.data('keys').split(',');

			filterEl.data('keys', keys);

			inputEls.first().attr('name', keys[0]);
			inputEls.last().attr('name', keys[1]);

			inputEls
				.attr('min', selectedEl.data('min'))
				.attr('max', selectedEl.data('max'));
		}

		// Add the new button to the filter list
		$('#filtration').append(filterEl);

		caas.compendium.filters.active.push(filter);

		// No need to do anything else if we're in pause mode
		if (caas.pause)
			return;

		caas.tooltip.init();

		// Open it up
		caas.compendium.activateFilter.call(filterEl);

		// Prevent the body click from closing it right away
		filterEl.addClass('-keep');

		// "Enabled" items can be searched right away
		if (type == 'enabled') {
			caas.compendium.getResults();
			// Only keep the -enabled filterEl active for so long, then close it
			setTimeout(function() {
				filterEl.removeClass('-active');
				filterEl.css('left', 'inherit');
			}, 500);
		}
	},
	clearFilters:function() {
		this.pause = true;

		$('#name-filter input').val('');
		this.sorting.call($('#name-filter .dropdown-item').first());

		$('#filtration .filter .delete').each(function() {
			caas.compendium.destroyFilter.call($(this));
		});

		this.pause = false;

		$('#pre-results').toggleAttr('hidden', false);
		$('#no-results').toggleAttr('hidden', true);
		$('#results .compendium-item:not(.-template)').remove();

		this.filters = Object.assign({}, defaultFilters);
		caas.storage.remove(game.slug + '-compendium-filters');
		caas.storage.remove(game.slug + '-compendium-results');

		$('#name-filter input').focus();
	},
	searchWidth:function() {
		var el = $(this);
		// Each character is worth "7".  "24" is from the input padding
		el.css('width', (((el.val().length + 2) * 7) + 24) + 'px');
	},
	search:function(event) {
		var oldName = (caas.compendium.filters.name || "") + ""; // Add "nothing" to unreference it
		caas.compendium.filters.name = $(this).val();
		if (oldName != caas.compendium.filters.name)
			caas.compendium.getResults();
	},
	getResults:function(page) {
		if (caas.compendium.pause)
			return;

		if (typeof this.jqXHR !== 'undefined')
			this.jqXHR.abort();

		if (typeof page === 'undefined')
			page = 1;

		this.filters.page = page;

		this.toggleLoader(true);

		// Clean up filters before sending it on
		var data = Object.assign({}, this.filters);
		// These are meaningless to the api call
		delete data.chapter;
		delete data.active;

		this.jqXHR = $.ajax({
			method: 'GET',
			url: '/api/' + this.filters.chapter,
			data: data,
			dataType: 'json'
		})
		.done(this.buildResults)
		.always(function() {
			caas.compendium.toggleLoader(false);
		});
	},
	toggleLoader:function(loading) {
		$('#select-filters > button > i').toggleClass('fa-filter', ! loading).toggleClass('fa-cog fa-spin', loading);
	},
	buildResults:function(results) {
		// Save the filters/results
		if ( ! caas.compendium.pause) {
			caas.storage.set(game.slug + '-compendium-filters', JSON.stringify(caas.compendium.filters));
			caas.storage.set(game.slug + '-compendium-results', JSON.stringify(results));
		}

		var filters = caas.compendium.filters;

		$('#pre-results').toggleAttr('hidden', true); // Always hide the pre-results
		$('#no-results').toggleAttr('hidden', results.data.length != 0);

		if (results.data.length == 0)
			return;

		var containerEl = $('#results'),
			templateEl = containerEl.find('.-template');

		containerEl.find('.compendium-item:not(.-template)').remove();

		$.each(results.data, function(index, data) {
			var el = templateEl.clone(true);

			el = caas.compendium.buildRow(el, data);

			caas.lists.updateButtons();

			el.removeClass('-template');
			el.toggleAttr('hidden', false);
			el.insertBefore(templateEl);
		});

		caas.tooltip.init();

		caas.compendium.buildNavigation(results);
	},
	buildNavigation:function(results) {
		// Only show the nav if there are results
		$('#pageNumber').html(results.meta.current_page);

		['prev', 'next'].forEach(function(dir) {
			var pageLinkEl = $('.page-link.-' + dir),
				page = results.links[dir],
				available = page !== null;

			if (available) {
				pageLinkEl.toggleAttr('tabindex', false)
				page = page.replace(/^.*page=/, '');
			} else
				pageLinkEl.attr('tabindex', '-1');

			pageLinkEl
				.data('page', page)
				.closest('.page-item').toggleClass('disabled', ! available);
		});
	},
	paginate:function(event) {
		event.preventDefault();

		var el = $(this);

		if (el.closest('.page-item').hasClass('disabled'))
			return;

		// Update results
		caas.compendium.getResults(el.data('page'));
	},
	buildRow:function(el, data) {
		el.find('> img').attr('src', '/assets/' + game.slug + '/item/' + data.icon + '.png');
		el.find('.name').addClass('rarity-' + data.rarity).html(data.name);
		el.find('.ilvl').html(data.ilvl);
		el.find('.category').html(data.category);

		if (typeof data.recipes !== 'undefined') {
			var recipe = data.recipes[0]; // Only use data from the first recipe

			el.find('.recipes .level').html(recipe.level);
			if (recipe.sublevel !== null && recipe.sublevel > 0)
				for (var i = 0; i < recipe.sublevel; i++)
					el.find('.recipes .level').append('<span class="sublevel-icon"></span>');

			el.find('.recipes .job img').first().attr('src', '/assets/' + game.slug + '/jobs/crafting-' + recipe.job.icon + '.png');

			if (data.recipes.length == 2) {
				el.find('.recipes .job img').last().attr('src', '/assets/' + game.slug + '/jobs/crafting-' + data.recipes[1].job.icon + '.png');
				el.find('.recipes .multiple').remove();
			} else if (data.recipes.length > 2) {
				el.find('.recipes .job img').last().remove();
				el.find('.recipes .multiple').html('✚').attr('data-toggle', 'tooltip').attr('title', recipe.job.icon + ' + ' + (data.recipes.length - 1) + ' others');
			} else {
				el.find('.recipes .multiple').remove();
				el.find('.recipes .job img').last().remove();
			}
		} else
			el.find('.recipes').remove();

		if (typeof data.equipment !== 'undefined') {
			el.find('.equipment .level').html(data.equipment.level);

			el.find('.equipment .job img').first().attr('src', '/assets/' + game.slug + '/jobs/' + data.equipment.jobs[0].icon + '.png');

			if (data.equipment.jobs.length == 2) {
				el.find('.equipment .job img').last().attr('src', '/assets/' + game.slug + '/jobs/' + data.equipment.jobs[1].icon + '.png');
				el.find('.equipment .multiple').remove();
			} else if (data.equipment.jobs.length > 2) {
				el.find('.equipment .job img').last().remove();
				el.find('.equipment .multiple').html('✚').attr('data-toggle', 'tooltip').attr('title', data.equipment.jobs[0].icon + ' + ' + (data.equipment.jobs.length - 1) + ' others');
			} else {
				el.find('.equipment .multiple').remove();
				el.find('.equipment .job img').last().remove();
			}
		} else
			el.find('.equipment').remove();

		el.find('.add-to-list').data('id', data.id);

		return el;
	},
	restoreFilters:function() {
		this.pause = true;

		var savedFilters = JSON.parse(caas.storage.get(game.slug + '-compendium-filters')) || Object.assign({}, this.filters),
			savedResults = JSON.parse(caas.storage.get(game.slug + '-compendium-results'));

		// Restore the last set of results
		if (savedResults !== null && typeof savedResults !== 'undefined' && typeof savedResults.data !== 'undefined')
			this.buildResults(savedResults);

		// Update Chapter select
		this.chapterSelect.call($('#chapter-select [data-chapter="' + savedFilters.chapter + '"]'));

		// Re-widen, and populate, search
		$('#filtration input[name=name]').val(savedFilters.name);
		this.searchWidth.call($('input[name=name]'));

		// Repopulate sorting
		this.sorting.call($('#name-filter .dropdown-item[data-sorting="' + savedFilters.sorting + '"][data-ordering="' + savedFilters.ordering + '"]'));

		// Re-populate any filters
		if (savedFilters.active.length > 0)
			for (var i = 0; i < savedFilters.active.length; i++) {
				var activateFilter = savedFilters.active[i],
					filterDropdownOption = $('#select-filters .dropdown-item[data-filter="' + activateFilter + '"]'),
					filter = filterDropdownOption.data('filter'),
					type = filterDropdownOption.data('type');

				this.createFilter.call(filterDropdownOption);

				var filterEl = $('#filtration .filter').last();

				if (type == 'enabled') {
					// Do nothing, job is done
				} else if (type == 'single') {
					// Populate the value
					if (typeof savedFilters[filter] !== 'undefined')
						filterEl.find('input').val(savedFilters[filter]);
				} else if (type == 'range') {
					// Populate the values
					filterEl.find('input').each(function() {
						var inputEl = $(this);
						inputEl.val(savedFilters[inputEl.attr('name')])
					});
				} else {
					// Special cases
					if (typeof savedFilters[filter] !== 'undefined') {
						var vals = savedFilters[filter].split(',');

						for (var j = 0; j < vals.length; j++) {
							filterEl.find('input[name="' + filter + '[]"][value="' + vals[j] + '"]').prop('checked', true);
						}
					}
				}

				this.closeFilter();
				filterEl.removeClass('-active');
				filterEl.css('left', 'inherit');
			}

		$('#name-filter input').focus();

		this.filters = savedFilters;

		caas.tooltip.init();

		this.pause = false;
	},
	sorting:function(event) {
		if (typeof event !== 'undefined')
			event.preventDefault();

		var el = $(this);

		// Activate this item
		el.siblings('.dropdown-item').removeClass('active');
		el.addClass('active');

		// Pull the icon into the display spot
		$('#name-filter .dropdown-toggle').html(el.html());

		// Save this sorting to the filter
		caas.compendium.filters.sorting = el.data('sorting');
		caas.compendium.filters.ordering = el.data('ordering');

		caas.compendium.getResults();
	},
	toggleFilter:function() {
		$('html').toggleClass('filter-open');
	}
};

// $(function() { caas.core.initializePage('compendium'); });
