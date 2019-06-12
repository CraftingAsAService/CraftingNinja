/**
 * Compendium Page
 */

'use strict';

Vue.component('ninja-dropdown', require('../components/NinjaDropdown.vue').default);

const compendium = new Vue({
	el: '#compendium',
	data: {
		firstLoad: true,
		searchTerm: typeof searchTerm !== 'undefined' ? searchTerm : '',
		chapter: 'items',
		activeFilters: [],
		noResults: true,
		results: {
			data: [],
			links: {},
			meta: {},
		},
		// These are submitted as parameters
		filters: {},
		sorting: 'name:asc',
		perPage: 15,
		// Setting to pass off to Ninja Dropdown
		ninjaFilters: {
			item: itemFilters,
			recipe: recipeFilters,
			equipment: equipmentFilters,
			sorting: sortingFilters,
			perPage: perPageFilters
		}
	},
	mounted:function() {
		this.initializeDropdowns();

		$('.search-form').on('submit', function(event) {
			event.preventDefault();
			compendium.searchTerm = $(this).find('input:visible').val();
			compendium.search();
			return false;
		}).find('input:visible').on('keyup', function(event) {
			if (event.which == 13)
				$(this).closest('form').trigger('submit');
		});
	},
	methods: {
		initializeDropdowns:function() {
			var thisObject = this;

			$('#compendium').find('select.cs-select').each(function() {
				var compendiumVar = $(this).data('compendium-var');
				new SelectFx(this, {
					onChange:function(val) {
						compendium[compendiumVar] = val;
					}
				});

				// Set initial value
				thisObject[compendiumVar] = $(this).val();
			});

			this.activeFilters = [];

			$.each(this.ninjaFilters[this.chapter], function() {
				thisObject.activeFilters.push(this.key);
			});

			this.applyFilters();
		},
		search:function() {
			var call = 'items';

			// TODO search "Blue Dye"
			// TODO search "Moogle"

			if (this.chapters == 'quest')
				call = 'quests';
			else if (this.chapters == 'mob')
				call = 'mobs';

			var data = Object.assign({}, this.filters);

			data.name = this.searchTerm;
			data.sorting = this.sorting.split(':')[0];
			data.ordering = this.sorting.split(':')[1];
			data.perPage = this.perPage;

			axios
				.post('/api/' + call, data)
				.then(response => {
					this.results = response.data;
					this.firstLoad = false;
				})
				.catch(error => console.log(error));
		},
		applyFilters:function() {
			var thisObject = this;

			$.each(this.ninjaFilters[this.chapter], function() {
				thisObject.applyFilter(this.key);
			});

			// Clear the page
			if (typeof this.filters.page !== 'undefined')
				this.filters.delete('page');

			this.search();
		},
		applyFilter:function(filterName) {
			var widgetEl = $('.widget.-filter.-' + filterName),
				type = widgetEl.data('type');

			if ( ! widgetEl.is(':visible'))
			{
				if (type == 'range') {
					var sliderEl = widgetEl.find('.slider-range'),
						keys = sliderEl.data('keys').split(',');
					this.filters.delete(keys[0]);
					this.filters.delete(keys[1]);
				} else {
					this.filters.delete(filterName);
				}
			}
			else
			{
				if (type == 'range') {
					var min = parseInt(widgetEl.find('.min').val()),
						max = parseInt(widgetEl.find('.max').val());
					this.filters[filterName + 'Min'] = min;
					this.filters[filterName + 'Max'] = max;
				} else if (type == 'multiple') {
					var values = widgetEl.find('input:checkbox:checked').map(function() {
						return this.value;
					}).get();
					this.filters[filterName] = values;
				}
			}
		},
		previousPage:function() {

		},
		nextPage:function() {

		},
		searchFocus:function() {
			$('.search-form :input').focus().select();
		},
		onNinjaDropdownClick:function(key, value) {
			if (key == 'filter')
				this.activeFilters.push(value);
			else
				this[key] = value;
		}
	}
});
