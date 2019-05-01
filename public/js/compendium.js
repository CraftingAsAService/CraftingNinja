(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/compendium"],{

/***/ "./resources/js/components/NinjaDropdown.vue":
/*!***************************************************!*\
  !*** ./resources/js/components/NinjaDropdown.vue ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

throw new Error("Module build failed (from ./node_modules/vue-loader/lib/index.js):\nTypeError: Cannot read property 'parseComponent' of undefined\n    at parse (/Users/nwright/Projects/Personal/craftingasaservice/crafting.ninja/node_modules/@vue/component-compiler-utils/dist/parse.js:14:23)\n    at Object.module.exports (/Users/nwright/Projects/Personal/craftingasaservice/crafting.ninja/node_modules/vue-loader/lib/index.js:67:22)");

/***/ }),

/***/ "./resources/js/pages/compendium.js":
/*!******************************************!*\
  !*** ./resources/js/pages/compendium.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Compendium Page
 */


Vue.component('ninja-dropdown', __webpack_require__(/*! ../components/NinjaDropdown.vue */ "./resources/js/components/NinjaDropdown.vue")["default"]);
var compendium = new Vue({
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
      meta: {}
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
  mounted: function mounted() {
    this.initializeDropdowns();
    this.buildRanges();
    if (this.searchTerm) this.search();
    $('.search-form').on('submit', function (event) {
      event.preventDefault();
      compendium.searchTerm = $(this).find('input:visible').val();
      compendium.search();
      return false;
    }).find('input:visible').on('keyup', function (event) {
      if (event.which == 13) $(this).closest('form').trigger('submit');
    });
  },
  methods: {
    initializeDropdowns: function initializeDropdowns() {
      $('#compendium').find('select.cs-select').each(function () {
        var compendiumVar = $(this).data('compendium-var');
        new SelectFx(this, {
          onChange: function onChange(val) {
            compendium[compendiumVar] = val;
          }
        });
      });
    },
    search: function search() {
      var _this = this;

      var call = 'items'; // TODO search "Blue Dye"
      // TODO search "Moogle"

      if (this.chapters == 'quests') call = 'quests';
      var data = Object.assign({}, this.filters);
      data.name = this.searchTerm;
      data.sorting = this.sorting.split(':')[0];
      data.ordering = this.sorting.split(':')[1];
      data.perPage = this.perPage;
      axios.post('/api/' + call, data).then(function (response) {
        _this.results = response.data;
        _this.firstLoad = false;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    buildRanges: function buildRanges() {
      $('.slider-range').each(function () {
        var el = $(this),
            domEl = el[0],
            min = parseInt(el.data('min')),
            max = parseInt(el.data('max')),
            snapEls = [el.parent().find('.min'), el.parent().find('.max')];
        noUiSlider.create(domEl, {
          start: [min, max],
          connect: true,
          step: 1,
          range: {
            'min': [min],
            'max': [max]
          }
        });
        domEl.noUiSlider.on('update', function (values, key) {
          snapEls[key].html(values[key].replace('.00', ''));
        });
      });
    },
    removeFilter: function removeFilter(filterName) {
      this.activeFilters = this.activeFilters.filter(function (value) {
        return value != filterName;
      });
      this.applyFilter(filterName);
    },
    applyFilter: function applyFilter(filterName) {
      var widgetEl = $('.widget.-filter.-' + filterName),
          type = widgetEl.data('type');

      if (!widgetEl.is(':visible')) {
        if (type == 'range') {
          var sliderEl = widgetEl.find('.slider-range'),
              keys = sliderEl.data('keys').split(',');
          this.filters["delete"](keys[0]);
          this.filters["delete"](keys[1]);
        } else {
          this.filters["delete"](filterName);
        }
      } else {
        if (type == 'range') {
          var sliderEl = widgetEl.find('.slider-range'),
              keys = sliderEl.data('keys').split(','),
              min = parseInt(sliderEl.parent().find('.min').html()),
              max = parseInt(sliderEl.parent().find('.max').html());
          this.filters[keys[0]] = min;
          this.filters[keys[1]] = max;
        } else if (type == 'multiple') {
          var values = widgetEl.find('input:checkbox:checked').map(function () {
            return this.value;
          }).get();
          this.filters[filterName] = values;
        }
      } // Clear the page


      if (typeof this.filters.page !== 'undefined') this.filters["delete"]('page');
      this.search();
    },
    previousPage: function previousPage() {},
    nextPage: function nextPage() {},
    searchFocus: function searchFocus() {
      $('.search-form :input').focus().select();
    },
    onNinjaDropdownClick: function onNinjaDropdownClick(key, value) {
      if (key == 'filter') this.activeFilters.push(value);else this[key] = value;
    }
  }
});

/***/ }),

/***/ 1:
/*!************************************************!*\
  !*** multi ./resources/js/pages/compendium.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/nwright/Projects/Personal/craftingasaservice/crafting.ninja/resources/js/pages/compendium.js */"./resources/js/pages/compendium.js");


/***/ })

},[[1,"/js/manifest"]]]);