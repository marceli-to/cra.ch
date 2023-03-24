import LazyLoad from '../vendor/lazyload';
import vhCheck from '../vendor/vhcheck';

var Utils = (function() {
	
	// selectors
	var selectors = {
    html:      'html',
    body:      'body',
    btnTouch:  '[data-touch]',
    btnMore:   '[data-more]',
    btnLess:   '[data-less]',
    btnToggle: '[data-toggle]',
    btnScrollTo: '[data-scroll-to]',
  };

  var classes = {
    active: 'is-active',
    visible: 'is-visible',
    touched: 'is-touched',
  };

  // Init
  var _initialize = function() {
    _bind();
    const vhc = vhCheck();
  };

  // Bind events
  var _bind = function() {
    $(selectors.btnTouch).on('touchstart', function(e) {
      $(this).addClass(classes.touched);
    });

    $(selectors.btnTouch).on('touchend', function(e) {
      $(this).removeClass(classes.touched);
    });

    $(selectors.btnScrollTo).on('click', function(e) {
      var distance = $('.' + $(this).data('scrollTo')).offset().top - 20;
      $.scrollTo(distance, 0);
    });

    $(selectors.btnToggle).on('click', function(e) {
      $(this).toggleClass(classes.active);
      $(this).next('div').toggle();
    });

    $(selectors.btnMore).on('click', function(e) {
      $(this).hide();
      $(this).prev('div').hide();
      $(this).next('div').show();
    });

    $(selectors.btnLess).on('click', function(e) {
      $(this).parent('div').hide();
      $(this).parent('div').prev('a').show();
      $(this).parent('div').prev('a').prev('div').show();
    });

    $('[data-hover]').on('mouseover', function(){
      $('['+ $(this).data("hover")+']').show();
    });
    $('[data-hover]').on('mouseout', function(){
      $('['+ $(this).data("hover")+']').hide();
    });

    // $(selectors.btnMore).on('click', function(e) {
    //   $('a[data-more]').hide();
    //   $('a[data-less]').show();
    //   $(this).find('.js-hidden').show();
    // });

    // $(selectors.btnLess).on('click', function(e) {
    //   $(this).parent('div').hide();
    //   $(this).parent('div').prev('a').show();
    //   $(this).parent('div').prev('a').prev('div').show();
    // });


    var lazyLoadInstance = new LazyLoad();
  };


  /* --------------------------------------------------------------
    * RETURN PUBLIC METHODS
    * ------------------------------------------------------------ */

  return {
    init:  _initialize,
  };
	
})();

// Initialize
$(function() {
  Utils.init();
});

