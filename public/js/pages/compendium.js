(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/pages/compendium"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/NinjaBagButton.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/NinjaBagButton.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['text', 'icon', 'type', 'id', 'img'],
  methods: {
    add: function add() {
      this.$eventBus.$emit('addToCart', this.id, this.type, 1, this.img, this.$refs.ninjaBagButton); // axios
      // 	.post('/knapsack', {
      // 		'id': this.id,
      // 		'type': this.type,
      // 		'quantity': 1
      // 	})
      // 	.then(response => {
      // 		// bag.refresh();
      // 	})
      // 	.catch(error => console.log(error));
    }
  }
});

/***/ }),

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/NinjaBagButton.vue?vue&type=template&id=45d8b91b&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/NinjaBagButton.vue?vue&type=template&id=45d8b91b& ***!
  \*****************************************************************************************************************************************************************************************************************/
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
    "a",
    {
      ref: "ninjaBagButton",
      staticClass: "btn btn-primary-inverse btn-block btn-icon",
      attrs: { href: "#" },
      on: {
        click: function($event) {
          $event.preventDefault()
          return _vm.add($event)
        }
      }
    },
    [
      _c("i", { class: _vm.icon }),
      _vm._v(" "),
      _c("span", { domProps: { innerHTML: _vm._s(_vm.text) } })
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



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

/***/ "./resources/js/components/NinjaBagButton.vue":
/*!****************************************************!*\
  !*** ./resources/js/components/NinjaBagButton.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NinjaBagButton_vue_vue_type_template_id_45d8b91b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NinjaBagButton.vue?vue&type=template&id=45d8b91b& */ "./resources/js/components/NinjaBagButton.vue?vue&type=template&id=45d8b91b&");
/* harmony import */ var _NinjaBagButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NinjaBagButton.vue?vue&type=script&lang=js& */ "./resources/js/components/NinjaBagButton.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NinjaBagButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NinjaBagButton_vue_vue_type_template_id_45d8b91b___WEBPACK_IMPORTED_MODULE_0__["render"],
  _NinjaBagButton_vue_vue_type_template_id_45d8b91b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/NinjaBagButton.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/NinjaBagButton.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/NinjaBagButton.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaBagButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./NinjaBagButton.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/NinjaBagButton.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaBagButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/NinjaBagButton.vue?vue&type=template&id=45d8b91b&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/NinjaBagButton.vue?vue&type=template&id=45d8b91b& ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaBagButton_vue_vue_type_template_id_45d8b91b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./NinjaBagButton.vue?vue&type=template&id=45d8b91b& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/NinjaBagButton.vue?vue&type=template&id=45d8b91b&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaBagButton_vue_vue_type_template_id_45d8b91b___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NinjaBagButton_vue_vue_type_template_id_45d8b91b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



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
Vue.component('ninja-bag-button', __webpack_require__(/*! ../components/NinjaBagButton.vue */ "./resources/js/components/NinjaBagButton.vue")["default"]);
var compendium = new Vue({
  el: '#compendium',
  data: {
    firstLoad: true,
    loading: false,
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
    collapsed: recipeFilters.filter(function (record) {
      return !record.expanded;
    }).map(function (record) {
      return record.key;
    }),
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
      });
    },
    nameUpdated: function nameUpdated() {
      // Reset the page if name is altered
      this.filters.page = 1;
      this.debouncedSearch();
    },
    toggleFilter: function toggleFilter(filter, value) {
      if (this.filters[filter].includes(value)) this.filters[filter] = this.filters[filter].filter(function (filterValue) {
        return filterValue != value;
      });else this.filters[filter].push(value);
      this.search();
    },
    toggleCollapse: function toggleCollapse(section) {
      if (this.collapsed.includes(section)) this.collapsed = this.collapsed.filter(function (value) {
        return value != section;
      });else this.collapsed.push(section);
      this.search();
    },
    search: function search() {
      var _this = this;

      var call = 'items'; // TODO need 128x128 imagery

      if (this.chapter == 'quest') call = 'quests';else if (this.chapter == 'mob') call = 'mobs'; // Clear data of any vue/observer interference

      var data = JSON.parse(JSON.stringify(Object.assign({}, this.filters))); // Remove any values that don't match what the chapter expects
      //  And remove anything collapsed sections

      var allowableFields = [];

      for (var prop in this.ninjaFilters[this.chapter]) {
        if (this.collapsed.includes(this.ninjaFilters[this.chapter][prop].key)) continue;

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
      data.page = this.filters.page;
      this.loading = true;
      axios.post('/api/' + call, data).then(function (response) {
        _this.results = response.data;
        _this.loading = _this.firstLoad = false;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    previousPage: function previousPage() {
      this.filters.page = this.results.meta.current_page - 1;
      this.search();
    },
    nextPage: function nextPage() {
      this.filters.page = this.results.meta.current_page + 1;
      this.search();
    },
    ninjaDropdownUpdated: function ninjaDropdownUpdated(key, value) {
      this[key] = value; // Reset the page if any of these were altered

      this.filters.page = 1;
      this.search();
    },
    addToBag: function addToBag() {}
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