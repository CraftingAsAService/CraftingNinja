/**
 * Compendium Page
 */

'use strict';

Vue.component('ninja-dropdown', require('../components/NinjaDropdown.vue').default);

const compendium = new Vue({
	el: '#compendium',
	data: {
		firstLoad: true,
		chapter: 'recipe',
		noResults: true,
		results: {
			data: [],
			links: {},
			meta: {},
		},
		// These are submitted as parameters
		filters: {
			name: searchTerm,
			// Arrays need pre-defined as arrays
			rclass: [],
			sublevel: [],
			rarity: [],
			eclass: [],
		},
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

		if (this.filters.name != '')
			this.search();
	},
	created:function() {
		this.debouncedSearch = _.debounce(this.search, 250);
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

			// this.activeFilters = [];

			// for (var i = 0; i < this.ninjaFilters[this.chapter].length; i++)
			// 	this.activeFilters.push(this.ninjaFilters[this.chapter][i].key);

			// this.applyFilters();
		},
		search:function() {
			var call = 'items';

			// TODO search "Blue Dye"
			// TODO search "Moogle"

			if (this.chapter == 'quest')
				call = 'quests';
			else if (this.chapter == 'mob')
				call = 'mobs';

			// Clear data of any vue/observer interference
			var data = JSON.parse(JSON.stringify(Object.assign({}, this.filters)));

			// Remove any values that don't match what the chapter expects
			var allowableFields = [];
			for (var prop in this.ninjaFilters[this.chapter])
				if (this.ninjaFilters[this.chapter][prop].type == 'range')
				{
					allowableFields.push(this.ninjaFilters[this.chapter][prop].key + 'Min');
					allowableFields.push(this.ninjaFilters[this.chapter][prop].key + 'Max');
				}
				else
					allowableFields.push(this.ninjaFilters[this.chapter][prop].key);

			// Any empty values also need removed
			for (var prop in data)
				if ( ! data[prop].length || ! allowableFields.includes(prop))
					delete data[prop];

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
		// applyFilters:function() {
		// 	for (var i = 0; i < this.ninjaFilters[this.chapter].length; i++)
		// 		this.applyFilter(this.ninjaFilters[this.chapter][i].key);

		// 	// Clear the page
		// 	if (typeof this.filters.page !== 'undefined')
		// 		this.filters.delete('page');

		// 	this.search();
		// },
		// applyFilter:function(filterName) {
		// 	var widgetEl = $('.widget.-filter.-' + filterName),
		// 		type = widgetEl.data('type');

		// 	if ( ! widgetEl.is(':visible'))
		// 	{
		// 		if (type == 'range') {
		// 			var sliderEl = widgetEl.find('.slider-range'),
		// 				keys = sliderEl.data('keys').split(',');
		// 			this.filters.delete(keys[0]);
		// 			this.filters.delete(keys[1]);
		// 		} else {
		// 			this.filters.delete(filterName);
		// 		}
		// 	}
		// 	else
		// 	{
		// 		if (type == 'range') {
		// 			var min = parseInt(widgetEl.find('.min').val()),
		// 				max = parseInt(widgetEl.find('.max').val());
		// 			this.filters[filterName + 'Min'] = min;
		// 			this.filters[filterName + 'Max'] = max;
		// 		} else if (type == 'multiple') {
		// 			var values = widgetEl.find('input:checkbox:checked').map(function() {
		// 				return this.value;
		// 			}).get();
		// 			this.filters[filterName] = values;
		// 		}
		// 	}
		// },
		previousPage:function() {

		},
		nextPage:function() {

		},
		// searchFocus:function() {
		// 	$('.search-form :input').focus().select();
		// },
		onNinjaDropdownClick:function(key, value) {
			// if (key == 'filter')
			// 	this.activeFilters.push(value);
			// else
				this[key] = value;
		}
	}
});
