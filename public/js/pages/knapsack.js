(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/pages/knapsack"],{

/***/ "./resources/js/pages/knapsack.js":
/*!****************************************!*\
  !*** ./resources/js/pages/knapsack.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Knapsack Page
 */


var knapsack = new Vue({
  el: '#knapsack',
  data: {
    contents: ninjaCartContents || [],
    active: null
  },
  watch: {
    active: {
      handler: function handler() {
        this.$eventBus.$emit('updateCart', this.active.id, this.active.type, this.active.quantity);
      },
      deep: true
    }
  },
  methods: {
    updateCart: function updateCart() {
      console.log('updateCartPre');
      this.$eventBus.$emit('updateCart', this.contents);
      this.$eventBus.$emit('addToCart', this.id, this.type, 1, this.img, this.$refs.ninjaBagButton);
    },
    removeFromCart: function removeFromCart(index) {
      this.contents.splice(index, 1);
      this.$eventBus.$emit('removeFromCart', index, 'index');
    },
    clearCart: function clearCart() {
      this.contents = [];
      this.$eventBus.$emit('clearCart');
    },
    activate: function activate(index) {
      this.active = ninjaCartContents[index];
    }
  }
});

/***/ }),

/***/ 1:
/*!**********************************************!*\
  !*** multi ./resources/js/pages/knapsack.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/nwright/Projects/Personal/craftingasaservice/crafting.ninja/resources/js/pages/knapsack.js */"./resources/js/pages/knapsack.js");


/***/ })

},[[1,"/js/manifest"]]]);