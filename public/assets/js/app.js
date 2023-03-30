/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/web/app.js":
/*!*********************************!*\
  !*** ./resources/js/web/app.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! ./modules/menu.js */ "./resources/js/web/modules/menu.js");

__webpack_require__(/*! ./modules/lazy.js */ "./resources/js/web/modules/lazy.js");

__webpack_require__(/*! ./modules/vhcheck.js */ "./resources/js/web/modules/vhcheck.js");

__webpack_require__(/*! ./modules/touch.js */ "./resources/js/web/modules/touch.js");

__webpack_require__(/*! ./modules/project.js */ "./resources/js/web/modules/project.js");

/***/ }),

/***/ "./resources/js/web/modules/lazy.js":
/*!******************************************!*\
  !*** ./resources/js/web/modules/lazy.js ***!
  \******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _vendor_lazyload__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../vendor/lazyload */ "./resources/js/web/vendor/lazyload.js");
/* harmony import */ var _vendor_lazyload__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_vendor_lazyload__WEBPACK_IMPORTED_MODULE_0__);


(function () {
  document.addEventListener("DOMContentLoaded", function () {
    var lazyLoadInstance = new _vendor_lazyload__WEBPACK_IMPORTED_MODULE_0___default.a();
  });
})();

/***/ }),

/***/ "./resources/js/web/modules/menu.js":
/*!******************************************!*\
  !*** ./resources/js/web/modules/menu.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function () {
  var classes = {
    visible: 'is-visible',
    block: 'is-block',
    active: 'is-active',
    hidden: 'is-hidden',
    current: 'is-current'
  };
  var selectors = {
    menu: '[data-menu]',
    btnMenu: '[data-menu-btn]',
    btnMenuItemParent: '[data-menu-parent]'
  };

  var init = function init() {
    var btnMenu = document.querySelector(selectors.btnMenu);

    if (btnMenu) {
      btnMenu.addEventListener("click", toggle, false);
    }

    var btnMenuItemParent = document.querySelectorAll(selectors.btnMenuItemParent);

    if (btnMenuItemParent) {
      btnMenuItemParent.forEach(function (btn) {
        btn.addEventListener("click", function () {
          toggleItems(btn);
        }, false);
      });
    }
  };

  var toggle = function toggle() {
    var btnMenu = document.querySelector(selectors.btnMenu);
    btnMenu.classList.toggle(classes.active);
    var menu = document.querySelector(selectors.menu);
    menu.classList.toggle(classes.visible); // if the menu is not visible, hide all items

    if (!menu.classList.contains(classes.visible)) {
      hideAllItems();
    }
  };

  var toggleItems = function toggleItems(btn) {
    if (btn.classList.contains(classes.active)) {
      btn.classList.remove(classes.active);
      btn.nextElementSibling.classList.remove(classes.block);
      return;
    }

    var ul = btn.nextElementSibling;
    ul.classList.add(classes.block);
    btn.classList.add(classes.active);
  };

  var hideAllItems = function hideAllItems() {
    var uls = document.querySelectorAll(selectors.btnMenuItemParent + ' + ul');
    uls.forEach(function (ul) {
      ul.previousElementSibling.classList.remove(classes.active);
      ul.classList.remove(classes.block);
    });
  };

  init();
})();

/***/ }),

/***/ "./resources/js/web/modules/project.js":
/*!*********************************************!*\
  !*** ./resources/js/web/modules/project.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function () {
  var classes = {
    visible: 'is-visible',
    active: 'is-active'
  };
  var selectors = {
    listItem: 'li.is-visible a[data-project]',
    listItemFirst: 'li.is-visible:first-child a[data-project]',
    imageItem: 'picture[data-project]',
    btnInfo: '.js-btn-project-info',
    wrapperInfo: '.js-project-info'
  };

  var init = function init() {
    // Mouse over project list
    var listItems = document.querySelectorAll(selectors.listItem);
    var listItemFirst = listItems[0];
    var listItemsArray = [].slice.call(listItems);
    var imageItems = document.querySelectorAll(selectors.imageItem);
    var imageItemsArray = [].slice.call(imageItems);

    if ("ontouchstart" in document.documentElement == false) {
      listItemsArray.forEach(function (item) {
        item.addEventListener("mouseover", function () {
          var id = item.dataset.project;
          imageItemsArray.forEach(function (image) {
            image.classList.add('is-hidden');
          });
          var targetImage = document.querySelector('picture[data-project="' + id + '"]');
          targetImage.classList.remove('is-hidden');
        });
        item.addEventListener("mouseleave", function () {
          imageItemsArray.forEach(function (image) {
            image.classList.add('is-hidden');
          }); // const id = listItemFirst.dataset.project;

          var targetImage = document.querySelector('picture[data-project="preview"]');
          targetImage.classList.remove('is-hidden');
        });
      });
    } // Toggle info


    var btnInfo = document.querySelector(selectors.btnInfo);

    if (btnInfo) {
      btnInfo.addEventListener("click", toggleInfo, false);
    }
  };

  var toggleInfo = function toggleInfo() {
    var wrapperInfo = document.querySelector(selectors.wrapperInfo);
    wrapperInfo.classList.toggle(classes.visible);
  };

  init();
})();

/***/ }),

/***/ "./resources/js/web/modules/touch.js":
/*!*******************************************!*\
  !*** ./resources/js/web/modules/touch.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

(function () {
  var classes = {
    touched: 'is-touched'
  };
  var selectors = {
    // menu items
    menuItems: '.js-menu li a',
    // generic items
    globalItems: '[data-touch]'
  };

  var init = function init() {
    var menuItems = document.querySelectorAll(selectors.menuItems);
    var menuItemsArray = [].slice.call(menuItems);
    var globalItems = document.querySelectorAll(selectors.globalItems);
    var globalItemsArray = [].slice.call(globalItems);
    var itemsArray = [].concat(_toConsumableArray(menuItemsArray), _toConsumableArray(globalItemsArray));
    itemsArray.forEach(function (item) {
      item.addEventListener("touchstart", function () {
        this.classList.add(classes.touched);
      }, false);
      item.addEventListener("touchend", function () {
        this.classList.remove(classes.touched);
      }, false);
    });
  };

  init();
})();

/***/ }),

/***/ "./resources/js/web/modules/vhcheck.js":
/*!*********************************************!*\
  !*** ./resources/js/web/modules/vhcheck.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _vendor_vhcheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../vendor/vhcheck */ "./resources/js/web/vendor/vhcheck.js");
/* harmony import */ var _vendor_vhcheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_vendor_vhcheck__WEBPACK_IMPORTED_MODULE_0__);

var vhc = _vendor_vhcheck__WEBPACK_IMPORTED_MODULE_0___default()();

/***/ }),

/***/ "./resources/js/web/vendor/lazyload.js":
/*!*********************************************!*\
  !*** ./resources/js/web/vendor/lazyload.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

!function (n, t) {
  "object" == ( false ? undefined : _typeof(exports)) && "undefined" != typeof module ? module.exports = t() :  true ? !(__WEBPACK_AMD_DEFINE_FACTORY__ = (t),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : undefined;
}(this, function () {
  "use strict";

  function n() {
    return n = Object.assign || function (n) {
      for (var t = 1; t < arguments.length; t++) {
        var e = arguments[t];

        for (var i in e) {
          Object.prototype.hasOwnProperty.call(e, i) && (n[i] = e[i]);
        }
      }

      return n;
    }, n.apply(this, arguments);
  }

  var t = "undefined" != typeof window,
      e = t && !("onscroll" in window) || "undefined" != typeof navigator && /(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent),
      i = t && "IntersectionObserver" in window,
      o = t && "classList" in document.createElement("p"),
      a = t && window.devicePixelRatio > 1,
      r = {
    elements_selector: ".lazy",
    container: e || t ? document : null,
    threshold: 300,
    thresholds: null,
    data_src: "src",
    data_srcset: "srcset",
    data_sizes: "sizes",
    data_bg: "bg",
    data_bg_hidpi: "bg-hidpi",
    data_bg_multi: "bg-multi",
    data_bg_multi_hidpi: "bg-multi-hidpi",
    data_poster: "poster",
    class_applied: "applied",
    class_loading: "loading",
    class_loaded: "loaded",
    class_error: "error",
    class_entered: "entered",
    class_exited: "exited",
    unobserve_completed: !0,
    unobserve_entered: !1,
    cancel_on_exit: !0,
    callback_enter: null,
    callback_exit: null,
    callback_applied: null,
    callback_loading: null,
    callback_loaded: null,
    callback_error: null,
    callback_finish: null,
    callback_cancel: null,
    use_native: !1
  },
      c = function c(t) {
    return n({}, r, t);
  },
      u = function u(n, t) {
    var e,
        i = "LazyLoad::Initialized",
        o = new n(t);

    try {
      e = new CustomEvent(i, {
        detail: {
          instance: o
        }
      });
    } catch (n) {
      (e = document.createEvent("CustomEvent")).initCustomEvent(i, !1, !1, {
        instance: o
      });
    }

    window.dispatchEvent(e);
  },
      l = "src",
      s = "srcset",
      f = "sizes",
      d = "poster",
      _ = "llOriginalAttrs",
      g = "loading",
      v = "loaded",
      b = "applied",
      p = "error",
      h = "native",
      m = "data-",
      E = "ll-status",
      I = function I(n, t) {
    return n.getAttribute(m + t);
  },
      y = function y(n) {
    return I(n, E);
  },
      A = function A(n, t) {
    return function (n, t, e) {
      var i = "data-ll-status";
      null !== e ? n.setAttribute(i, e) : n.removeAttribute(i);
    }(n, 0, t);
  },
      k = function k(n) {
    return A(n, null);
  },
      L = function L(n) {
    return null === y(n);
  },
      w = function w(n) {
    return y(n) === h;
  },
      x = [g, v, b, p],
      O = function O(n, t, e, i) {
    n && (void 0 === i ? void 0 === e ? n(t) : n(t, e) : n(t, e, i));
  },
      N = function N(n, t) {
    o ? n.classList.add(t) : n.className += (n.className ? " " : "") + t;
  },
      C = function C(n, t) {
    o ? n.classList.remove(t) : n.className = n.className.replace(new RegExp("(^|\\s+)" + t + "(\\s+|$)"), " ").replace(/^\s+/, "").replace(/\s+$/, "");
  },
      M = function M(n) {
    return n.llTempImage;
  },
      z = function z(n, t) {
    if (t) {
      var e = t._observer;
      e && e.unobserve(n);
    }
  },
      R = function R(n, t) {
    n && (n.loadingCount += t);
  },
      T = function T(n, t) {
    n && (n.toLoadCount = t);
  },
      G = function G(n) {
    for (var t, e = [], i = 0; t = n.children[i]; i += 1) {
      "SOURCE" === t.tagName && e.push(t);
    }

    return e;
  },
      D = function D(n, t) {
    var e = n.parentNode;
    e && "PICTURE" === e.tagName && G(e).forEach(t);
  },
      V = function V(n, t) {
    G(n).forEach(t);
  },
      F = [l],
      j = [l, d],
      P = [l, s, f],
      S = function S(n) {
    return !!n[_];
  },
      U = function U(n) {
    return n[_];
  },
      $ = function $(n) {
    return delete n[_];
  },
      q = function q(n, t) {
    if (!S(n)) {
      var e = {};
      t.forEach(function (t) {
        e[t] = n.getAttribute(t);
      }), n[_] = e;
    }
  },
      H = function H(n, t) {
    if (S(n)) {
      var e = U(n);
      t.forEach(function (t) {
        !function (n, t, e) {
          e ? n.setAttribute(t, e) : n.removeAttribute(t);
        }(n, t, e[t]);
      });
    }
  },
      B = function B(n, t, e) {
    N(n, t.class_loading), A(n, g), e && (R(e, 1), O(t.callback_loading, n, e));
  },
      J = function J(n, t, e) {
    e && n.setAttribute(t, e);
  },
      K = function K(n, t) {
    J(n, f, I(n, t.data_sizes)), J(n, s, I(n, t.data_srcset)), J(n, l, I(n, t.data_src));
  },
      Q = {
    IMG: function IMG(n, t) {
      D(n, function (n) {
        q(n, P), K(n, t);
      }), q(n, P), K(n, t);
    },
    IFRAME: function IFRAME(n, t) {
      q(n, F), J(n, l, I(n, t.data_src));
    },
    VIDEO: function VIDEO(n, t) {
      V(n, function (n) {
        q(n, F), J(n, l, I(n, t.data_src));
      }), q(n, j), J(n, d, I(n, t.data_poster)), J(n, l, I(n, t.data_src)), n.load();
    }
  },
      W = ["IMG", "IFRAME", "VIDEO"],
      X = function X(n, t) {
    !t || function (n) {
      return n.loadingCount > 0;
    }(t) || function (n) {
      return n.toLoadCount > 0;
    }(t) || O(n.callback_finish, t);
  },
      Y = function Y(n, t, e) {
    n.addEventListener(t, e), n.llEvLisnrs[t] = e;
  },
      Z = function Z(n, t, e) {
    n.removeEventListener(t, e);
  },
      nn = function nn(n) {
    return !!n.llEvLisnrs;
  },
      tn = function tn(n) {
    if (nn(n)) {
      var t = n.llEvLisnrs;

      for (var e in t) {
        var i = t[e];
        Z(n, e, i);
      }

      delete n.llEvLisnrs;
    }
  },
      en = function en(n, t, e) {
    !function (n) {
      delete n.llTempImage;
    }(n), R(e, -1), function (n) {
      n && (n.toLoadCount -= 1);
    }(e), C(n, t.class_loading), t.unobserve_completed && z(n, e);
  },
      on = function on(n, t, e) {
    var i = M(n) || n;
    nn(i) || function (n, t, e) {
      nn(n) || (n.llEvLisnrs = {});
      var i = "VIDEO" === n.tagName ? "loadeddata" : "load";
      Y(n, i, t), Y(n, "error", e);
    }(i, function (o) {
      !function (n, t, e, i) {
        var o = w(t);
        en(t, e, i), N(t, e.class_loaded), A(t, v), O(e.callback_loaded, t, i), o || X(e, i);
      }(0, n, t, e), tn(i);
    }, function (o) {
      !function (n, t, e, i) {
        var o = w(t);
        en(t, e, i), N(t, e.class_error), A(t, p), O(e.callback_error, t, i), o || X(e, i);
      }(0, n, t, e), tn(i);
    });
  },
      an = function an(n, t, e) {
    !function (n) {
      n.llTempImage = document.createElement("IMG");
    }(n), on(n, t, e), function (n) {
      S(n) || (n[_] = {
        backgroundImage: n.style.backgroundImage
      });
    }(n), function (n, t, e) {
      var i = I(n, t.data_bg),
          o = I(n, t.data_bg_hidpi),
          r = a && o ? o : i;
      r && (n.style.backgroundImage = 'url("'.concat(r, '")'), M(n).setAttribute(l, r), B(n, t, e));
    }(n, t, e), function (n, t, e) {
      var i = I(n, t.data_bg_multi),
          o = I(n, t.data_bg_multi_hidpi),
          r = a && o ? o : i;
      r && (n.style.backgroundImage = r, function (n, t, e) {
        N(n, t.class_applied), A(n, b), e && (t.unobserve_completed && z(n, t), O(t.callback_applied, n, e));
      }(n, t, e));
    }(n, t, e);
  },
      rn = function rn(n, t, e) {
    !function (n) {
      return W.indexOf(n.tagName) > -1;
    }(n) ? an(n, t, e) : function (n, t, e) {
      on(n, t, e), function (n, t, e) {
        var i = Q[n.tagName];
        i && (i(n, t), B(n, t, e));
      }(n, t, e);
    }(n, t, e);
  },
      cn = function cn(n) {
    n.removeAttribute(l), n.removeAttribute(s), n.removeAttribute(f);
  },
      un = function un(n) {
    D(n, function (n) {
      H(n, P);
    }), H(n, P);
  },
      ln = {
    IMG: un,
    IFRAME: function IFRAME(n) {
      H(n, F);
    },
    VIDEO: function VIDEO(n) {
      V(n, function (n) {
        H(n, F);
      }), H(n, j), n.load();
    }
  },
      sn = function sn(n, t) {
    (function (n) {
      var t = ln[n.tagName];
      t ? t(n) : function (n) {
        if (S(n)) {
          var t = U(n);
          n.style.backgroundImage = t.backgroundImage;
        }
      }(n);
    })(n), function (n, t) {
      L(n) || w(n) || (C(n, t.class_entered), C(n, t.class_exited), C(n, t.class_applied), C(n, t.class_loading), C(n, t.class_loaded), C(n, t.class_error));
    }(n, t), k(n), $(n);
  },
      fn = ["IMG", "IFRAME", "VIDEO"],
      dn = function dn(n) {
    return n.use_native && "loading" in HTMLImageElement.prototype;
  },
      _n = function _n(n, t, e) {
    n.forEach(function (n) {
      return function (n) {
        return n.isIntersecting || n.intersectionRatio > 0;
      }(n) ? function (n, t, e, i) {
        var o = function (n) {
          return x.indexOf(y(n)) >= 0;
        }(n);

        A(n, "entered"), N(n, e.class_entered), C(n, e.class_exited), function (n, t, e) {
          t.unobserve_entered && z(n, e);
        }(n, e, i), O(e.callback_enter, n, t, i), o || rn(n, e, i);
      }(n.target, n, t, e) : function (n, t, e, i) {
        L(n) || (N(n, e.class_exited), function (n, t, e, i) {
          e.cancel_on_exit && function (n) {
            return y(n) === g;
          }(n) && "IMG" === n.tagName && (tn(n), function (n) {
            D(n, function (n) {
              cn(n);
            }), cn(n);
          }(n), un(n), C(n, e.class_loading), R(i, -1), k(n), O(e.callback_cancel, n, t, i));
        }(n, t, e, i), O(e.callback_exit, n, t, i));
      }(n.target, n, t, e);
    });
  },
      gn = function gn(n) {
    return Array.prototype.slice.call(n);
  },
      vn = function vn(n) {
    return n.container.querySelectorAll(n.elements_selector);
  },
      bn = function bn(n) {
    return function (n) {
      return y(n) === p;
    }(n);
  },
      pn = function pn(n, t) {
    return function (n) {
      return gn(n).filter(L);
    }(n || vn(t));
  },
      hn = function hn(n, e) {
    var o = c(n);
    this._settings = o, this.loadingCount = 0, function (n, t) {
      i && !dn(n) && (t._observer = new IntersectionObserver(function (e) {
        _n(e, n, t);
      }, function (n) {
        return {
          root: n.container === document ? null : n.container,
          rootMargin: n.thresholds || n.threshold + "px"
        };
      }(n)));
    }(o, this), function (n, e) {
      t && window.addEventListener("online", function () {
        !function (n, t) {
          var e;
          (e = vn(n), gn(e).filter(bn)).forEach(function (t) {
            C(t, n.class_error), k(t);
          }), t.update();
        }(n, e);
      });
    }(o, this), this.update(e);
  };

  return hn.prototype = {
    update: function update(n) {
      var t,
          o,
          a = this._settings,
          r = pn(n, a);
      T(this, r.length), !e && i ? dn(a) ? function (n, t, e) {
        n.forEach(function (n) {
          -1 !== fn.indexOf(n.tagName) && function (n, t, e) {
            n.setAttribute("loading", "lazy"), on(n, t, e), function (n, t) {
              var e = Q[n.tagName];
              e && e(n, t);
            }(n, t), A(n, h);
          }(n, t, e);
        }), T(e, 0);
      }(r, a, this) : (o = r, function (n) {
        n.disconnect();
      }(t = this._observer), function (n, t) {
        t.forEach(function (t) {
          n.observe(t);
        });
      }(t, o)) : this.loadAll(r);
    },
    destroy: function destroy() {
      this._observer && this._observer.disconnect(), vn(this._settings).forEach(function (n) {
        $(n);
      }), delete this._observer, delete this._settings, delete this.loadingCount, delete this.toLoadCount;
    },
    loadAll: function loadAll(n) {
      var t = this,
          e = this._settings;
      pn(n, e).forEach(function (n) {
        z(n, t), rn(n, e, t);
      });
    },
    restoreAll: function restoreAll() {
      var n = this._settings;
      vn(n).forEach(function (t) {
        sn(t, n);
      });
    }
  }, hn.load = function (n, t) {
    var e = c(t);
    rn(n, e);
  }, hn.resetStatus = function (n) {
    k(n);
  }, t && function (n, t) {
    if (t) if (t.length) for (var e, i = 0; e = t[i]; i += 1) {
      u(n, e);
    } else u(n, t);
  }(hn, window.lazyLoadOptions), hn;
});

/***/ }),

/***/ "./resources/js/web/vendor/vhcheck.js":
/*!********************************************!*\
  !*** ./resources/js/web/vendor/vhcheck.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

!function (e, n) {
  "object" == ( false ? undefined : _typeof(exports)) && "undefined" != typeof module ? module.exports = n() :  true ? !(__WEBPACK_AMD_DEFINE_FACTORY__ = (n),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : undefined;
}(this, function () {
  "use strict";

  var _r = function r() {
    return (_r = Object.assign || function (e) {
      for (var n, t = 1, o = arguments.length; t < o; t++) {
        for (var r in n = arguments[t]) {
          Object.prototype.hasOwnProperty.call(n, r) && (e[r] = n[r]);
        }
      }

      return e;
    }).apply(this, arguments);
  };

  function n() {
    var e,
        n,
        t = ((e = document.createElement("div")).style.cssText = "position: fixed; top: 0; height: 100vh; pointer-events: none;", document.documentElement.insertBefore(e, document.documentElement.firstChild), e),
        o = window.innerHeight,
        r = t.offsetHeight,
        i = r - o;
    return n = t, document.documentElement.removeChild(n), {
      vh: r,
      windowHeight: o,
      offset: i,
      isNeeded: 0 !== i,
      value: 0
    };
  }

  function i() {}

  function e() {
    var e = n();
    return e.value = e.offset, e;
  }

  var c = Object.freeze({
    noop: i,
    computeDifference: e,
    redefineVhUnit: function redefineVhUnit() {
      var e = n();
      return e.value = .01 * e.windowHeight, e;
    }
  });

  function u(e) {
    return "string" == typeof e && 0 < e.length;
  }

  var a = Object.freeze({
    cssVarName: "vh-offset",
    redefineVh: !1,
    method: e,
    force: !1,
    bind: !0,
    updateOnTouch: !1,
    onUpdate: i
  });
  var t = !1,
      o = [];

  try {
    var f = Object.defineProperty({}, "passive", {
      get: function get() {
        t = !0;
      }
    });
    window.addEventListener("test", f, f), window.removeEventListener("test", f, f);
  } catch (e) {
    t = !1;
  }

  function d(e, n) {
    o.push({
      eventName: e,
      callback: n
    }), window.addEventListener(e, n, !!t && {
      passive: !0
    });
  }

  function s() {
    o.forEach(function (e) {
      window.removeEventListener(e.eventName, e.callback);
    }), o = [];
  }

  function m(e, n) {
    document.documentElement.style.setProperty("--" + e, n.value + "px");
  }

  function h(e, n) {
    return _r({}, e, {
      unbind: s,
      recompute: n.method
    });
  }

  return function (e) {
    var n = Object.freeze(function (e) {
      if (u(e)) return _r({}, a, {
        cssVarName: e
      });
      if ("object" != _typeof(e)) return a;
      var n,
          t = {
        force: !0 === e.force,
        bind: !1 !== e.bind,
        updateOnTouch: !0 === e.updateOnTouch,
        onUpdate: (n = e.onUpdate, "function" == typeof n ? e.onUpdate : i)
      },
          o = !0 === e.redefineVh;
      return t.method = c[o ? "redefineVhUnit" : "computeDifference"], t.cssVarName = u(e.cssVarName) ? e.cssVarName : o ? "vh" : a.cssVarName, t;
    }(e)),
        t = h(n.method(), n);
    if (!t.isNeeded && !n.force) return t;
    if (m(n.cssVarName, t), n.onUpdate(t), !n.bind) return t;

    function o() {
      window.requestAnimationFrame(function () {
        var e = n.method();
        m(n.cssVarName, e), n.onUpdate(h(e, n));
      });
    }

    return t.unbind(), d("orientationchange", o), n.updateOnTouch && d("touchmove", o), t;
  };
});

/***/ }),

/***/ "./resources/sass/cms/app.scss":
/*!*************************************!*\
  !*** ./resources/sass/cms/app.scss ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/web/app.scss":
/*!*************************************!*\
  !*** ./resources/sass/web/app.scss ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!***************************************************************************************************!*\
  !*** multi ./resources/js/web/app.js ./resources/sass/web/app.scss ./resources/sass/cms/app.scss ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/marceli.to/Jamon.digital/Webroot/cristinarutz.ch/resources/js/web/app.js */"./resources/js/web/app.js");
__webpack_require__(/*! /Users/marceli.to/Jamon.digital/Webroot/cristinarutz.ch/resources/sass/web/app.scss */"./resources/sass/web/app.scss");
module.exports = __webpack_require__(/*! /Users/marceli.to/Jamon.digital/Webroot/cristinarutz.ch/resources/sass/cms/app.scss */"./resources/sass/cms/app.scss");


/***/ })

/******/ });