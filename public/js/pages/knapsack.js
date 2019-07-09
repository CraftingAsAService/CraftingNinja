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
        if (this.active) this.$eventBus.$emit('updateCart', this.active.id, this.active.type, this.active.quantity);
      },
      deep: true
    }
  },
  created: function created() {
    this.$eventBus.$on('cartChanged', this.cartChanged);
  },
  beforeDestroy: function beforeDestroy() {
    this.$eventBus.$off('cartChanged');
  },
  methods: {
    cartChanged: function cartChanged(cartContents) {
      // Align local cart data with the actual cart contents
      var newContents = [],
          oldContents = this.contents;

      for (var index in oldContents) {
        var cIndex = cartContents.findIndex(function (entry) {
          return entry.i == oldContents[index].id;
        });

        if (cIndex !== -1) {
          oldContents[index].quantity = cartContents[cIndex].q;
          newContents.push(oldContents[index]);
        }
      }

      this.contents = newContents;
    },
    updateCart: function updateCart() {
      this.$eventBus.$emit('updateCart', this.contents);
      this.$eventBus.$emit('addToCart', this.id, this.type, 1, this.img, this.$refs.ninjaBagButton);
    },
    removeFromCart: function removeFromCart(id, type) {
      this.deactivate();
      this.$eventBus.$emit('removeFromCart', id, type);
    },
    clearCart: function clearCart() {
      this.deactivate();
      this.$eventBus.$emit('clearCart');
    },
    activate: function activate(index) {
      this.active = this.contents[index];
    },
    deactivate: function deactivate() {
      this.active = null;
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