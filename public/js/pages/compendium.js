(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/pages/compendium"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/NinjaDropdown.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/NinjaDropdown.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['title', 'icon', 'placeholder', 'option', 'options'],
  data: function data() {
    return {
      displayTitle: null,
      open: false
    };
  },
  mounted: function mounted() {
    this.displayTitle = this.options[0].title;
  },
  methods: {
    close: function close() {
      this.open = false;
    },
    optionClick: function optionClick(key, value, title) {
      this.displayTitle = title;
      this.$emit('clicked', key, value);
      this.close();
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/NinjaDropdown.vue?vue&type=template&id=181e42e0&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/NinjaDropdown.vue?vue&type=template&id=181e42e0& ***!
  \****************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      directives: [
        {
          name: "click-outside",
          rawName: "v-click-outside",
          value: _vm.close,
          expression: "close"
        }
      ],
      staticClass: "post-filter__select ninja-dropdown"
    },
    [
      _c("label", { staticClass: "post-filter__label" }, [
        _c("i", { class: _vm.icon + " mr-1" }),
        _vm._v("\n\t\t" + _vm._s(_vm.title) + "\n\t")
      ]),
      _vm._v(" "),
      _c(
        "div",
        { class: "cs-select cs-skin-border " + (_vm.open ? " cs-active" : "") },
        [
          _c(
            "span",
            {
              staticClass: "cs-placeholder",
              on: {
                click: function($event) {
                  _vm.open = !_vm.open
                }
              }
            },
            [_vm._v(_vm._s(_vm.placeholder || _vm.displayTitle))]
          ),
          _vm._v(" "),
          _c(
            "ul",
            { staticClass: "cs-options" },
            _vm._l(_vm.options, function(optionData) {
              return _c(
                "li",
                {
                  on: {
                    click: function($event) {
                      return _vm.optionClick(
                        _vm.option,
                        optionData["key"],
                        optionData["title"]
                      )
                    }
                  }
                },
                [
                  _c("span", [
                    _c("i", {
                      class: "fas " + optionData["icon"] + " fa-fw mr-1"
                    }),
                    _vm._v(
                      "\n\t\t\t\t\t" +
                        _vm._s(optionData["title"]) +
                        "\n\t\t\t\t"
                    )
                  ])
                ]
              )
            }),
            0
          )
        ]
      )
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./resources/js/components/NinjaDropdown.vue":
/*!***************************************************!*\
  !*** ./resources/js/components/NinjaDropdown.vue ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NinjaDropdown_vue_vue_type_template_id_181e42e0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NinjaDropdown.vue?vue&type=template&id=181e42e0& */ "./resources/js/components/NinjaDropdown.vue?vue&type=template&id=181e42e0&");
/* harmony import */ var _NinjaDropdown_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NinjaDropdown.vue?vue&type=script&lang=js& */ "./resources/js/components/NinjaDropdown.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NinjaDropdown_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NinjaDropdown_vue_vue_type_template_id_181e42e0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _NinjaDropdown_vue_vue_type_template_id_181e42e0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/NinjaDropdown.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/NinjaDropdown.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/components/NinjaDropdown.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaDropdown_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./NinjaDropdown.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/NinjaDropdown.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaDropdown_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/NinjaDropdown.vue?vue&type=template&id=181e42e0&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/NinjaDropdown.vue?vue&type=template&id=181e42e0& ***!
  \**********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaDropdown_vue_vue_type_template_id_181e42e0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./NinjaDropdown.vue?vue&type=template&id=181e42e0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/NinjaDropdown.vue?vue&type=template&id=181e42e0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaDropdown_vue_vue_type_template_id_181e42e0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaDropdown_vue_vue_type_template_id_181e42e0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



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
    chapter: 'recipe',
    noResults: true,
    results: {
      data: [],
      links: {},
      meta: {}
    },
    // These are submitted as parameters
    filters: {
      name: searchTerm,
      // Arrays need pre-defined as arrays
      rclass: [],
      sublevel: [],
      rarity: [],
      eclass: []
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
  mounted: function mounted() {
    this.initializeDropdowns();
    if (this.filters.name != '') this.search();
  },
  created: function created() {
    this.debouncedSearch = _.debounce(this.search, 250);
  },
  methods: {
    initializeDropdowns: function initializeDropdowns() {
      var thisObject = this;
      $('#compendium').find('select.cs-select').each(function () {
        var compendiumVar = $(this).data('compendium-var');
        new SelectFx(this, {
          onChange: function onChange(val) {
            compendium[compendiumVar] = val;
          }
        }); // Set initial value

        thisObject[compendiumVar] = $(this).val();
      }); // this.activeFilters = [];
      // for (var i = 0; i < this.ninjaFilters[this.chapter].length; i++)
      // 	this.activeFilters.push(this.ninjaFilters[this.chapter][i].key);
      // this.applyFilters();
    },
    toggleFilter: function toggleFilter(filter, value) {
      if (this.filters[filter].includes(value)) this.filters[filter] = this.filters[filter].filter(function (filterValue) {
        return filterValue != value;
      });else this.filters[filter].push(value);
      this.debouncedSearch();
    },
    search: function search() {
      var _this = this;

      var call = 'items'; // TODO search "Blue Dye"
      // TODO search "Moogle"

      if (this.chapter == 'quest') call = 'quests';else if (this.chapter == 'mob') call = 'mobs'; // Clear data of any vue/observer interference

      var data = JSON.parse(JSON.stringify(Object.assign({}, this.filters))); // Remove any values that don't match what the chapter expects

      var allowableFields = [];

      for (var prop in this.ninjaFilters[this.chapter]) {
        if (this.ninjaFilters[this.chapter][prop].type == 'range') {
          allowableFields.push(this.ninjaFilters[this.chapter][prop].key + 'Min');
          allowableFields.push(this.ninjaFilters[this.chapter][prop].key + 'Max');
        } else allowableFields.push(this.ninjaFilters[this.chapter][prop].key);
      } // Any empty values also need removed


      for (var prop in data) {
        if (!data[prop].length || !allowableFields.includes(prop)) delete data[prop];
      }

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
    previousPage: function previousPage() {},
    nextPage: function nextPage() {},
    // searchFocus:function() {
    // 	$('.search-form :input').focus().select();
    // },
    onNinjaDropdownClick: function onNinjaDropdownClick(key, value) {
      // if (key == 'filter')
      // 	this.activeFilters.push(value);
      // else
      this[key] = value;
    }
  }
});

/***/ }),

/***/ "./resources/scss/alchemists/theme.scss":
/*!**********************************************!*\
  !*** ./resources/scss/alchemists/theme.scss ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/scss/app.scss":
/*!*********************************!*\
  !*** ./resources/scss/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/scss/pages/compendium.scss":
/*!**********************************************!*\
  !*** ./resources/scss/pages/compendium.scss ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/scss/pages/game-index.scss":
/*!**********************************************!*\
  !*** ./resources/scss/pages/game-index.scss ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!***********************************************************************************************************************************************************************************************!*\
  !*** multi ./resources/js/pages/compendium.js ./resources/scss/pages/compendium.scss ./resources/scss/pages/game-index.scss ./resources/scss/app.scss ./resources/scss/alchemists/theme.scss ***!
  \***********************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/nwright/Projects/Personal/craftingasaservice/crafting.ninja/resources/js/pages/compendium.js */"./resources/js/pages/compendium.js");
__webpack_require__(/*! /Users/nwright/Projects/Personal/craftingasaservice/crafting.ninja/resources/scss/pages/compendium.scss */"./resources/scss/pages/compendium.scss");
__webpack_require__(/*! /Users/nwright/Projects/Personal/craftingasaservice/crafting.ninja/resources/scss/pages/game-index.scss */"./resources/scss/pages/game-index.scss");
__webpack_require__(/*! /Users/nwright/Projects/Personal/craftingasaservice/crafting.ninja/resources/scss/app.scss */"./resources/scss/app.scss");
module.exports = __webpack_require__(/*! /Users/nwright/Projects/Personal/craftingasaservice/crafting.ninja/resources/scss/alchemists/theme.scss */"./resources/scss/alchemists/theme.scss");


/***/ })

},[[0,"/js/manifest"]]]);