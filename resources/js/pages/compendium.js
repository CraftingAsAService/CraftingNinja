/**
 * Compendium Page
 */

'use strict';

Vue.component('ninja-dropdown', require('../components/NinjaDropdown.vue').default);
Vue.component('ninja-bag-button', require('../components/NinjaBagButton.vue').default);

const compendium = new Vue({
	el: '#compendium',
	data: {
		firstLoad: true,
		loading: false,
		chapter: chapterStart || 'recipe',
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
			badditional: [],
			bclass: [],
		},
		collapsed: recipeFilters
					.filter(function(record) {
						return ! record.expanded;
					}).map(function(record) {
						return record.key;
					}),
		sorting: 'name:asc',
		perPage: 15,
		// Setting to pass off to Ninja Dropdown
		ninjaFilters: {
			books: booksFilters,
			item: itemFilters,
			recipe: recipeFilters,
			equipment: equipmentFilters,
			sorting: sortingFilters,
			perPage: perPageFilters
		}
	},
	mounted:function() {
		this.initializeDropdowns();

		this.parseInitialFilters();

		if (this.filters.name != '')
			this.search();
	},
	created:function() {
		this.debouncedSearch = _.debounce(this.search, 250);
	},
	methods: {
		parseInitialFilters:function() {
			if (chapterStart == 'books' && filterStart == 'mine')
				this.toggleFilter('badditional', 'mine');
		},
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
		},
		nameUpdated:function() {
			// Reset the page if name is altered
			this.filters.page = 1;

			this.debouncedSearch();
		},
		toggleFilter:function(filter, value) {
			if (this.filters[filter].includes(value))
				this.filters[filter] = this.filters[filter].filter(function(filterValue) {
					return filterValue != value;
				});
			else
				this.filters[filter].push(value);

			this.search();
		},
		toggleCollapse:function(section) {
			if (this.collapsed.includes(section))
				this.collapsed = this.collapsed.filter(function(value) {
					return value != section;
				});
			else
				this.collapsed.push(section);

			this.search();
		},
		search:function() {
			// Clear data of any vue/observer interference
			var data = JSON.parse(JSON.stringify(Object.assign({}, this.filters)));

			// Remove any values that don't match what the chapter expects
			//  And remove anything collapsed sections
			var allowableFields = [];
			for (var prop in this.ninjaFilters[this.chapter])
			{
				if (this.collapsed.includes(this.ninjaFilters[this.chapter][prop].key))
					continue;

				if (this.ninjaFilters[this.chapter][prop].type == 'range')
				{
					allowableFields.push(this.ninjaFilters[this.chapter][prop].key + 'Min');
					allowableFields.push(this.ninjaFilters[this.chapter][prop].key + 'Max');
				}
				else
					allowableFields.push(this.ninjaFilters[this.chapter][prop].key);
			}

			// Any empty values also need removed
			for (var prop in data)
				if ( ! data[prop].length || ! allowableFields.includes(prop))
					delete data[prop];

			data.sorting = this.sorting.split(':')[0];
			data.ordering = this.sorting.split(':')[1];
			data.perPage = this.perPage;
			data.page = this.filters.page;

			this.loading = true;
			axios
				.post('/api/' + this.chapter, data)
				.then(response => {
					this.results = response.data;
					this.loading = this.firstLoad = false;
				})
				.catch(error => console.log(error));
		},
		previousPage:function() {
			this.filters.page = this.results.meta.current_page - 1;
			this.search();
		},
		nextPage:function() {
			this.filters.page = this.results.meta.current_page + 1;
			this.search();
		},
		ninjaDropdownUpdated:function(key, value) {
			this[key] = value;

			// Reset the page if any of these were altered
			this.filters.page = 1;

			this.search();
		},
		addToBag:function() {

		}
	}
});
