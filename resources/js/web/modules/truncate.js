var Truncate = (function() {
	
	// selectors
	var selectors = {
    html:      'html',
    body:      'body',
    btnMore:   '[data-more]',
    btnLess:   '[data-less]',
  };

  var classes = {
    active: 'is-active',
    visible: 'is-visible',
    touched: 'is-touched',
  };

  // Init
  var _initialize = function() {
    _bind();
  };

  // Bind events
  var _bind = function() {

    $(selectors.btnMore).on('click', function(e) {
      $(this).hide();
      $(this).prev('div').addClass('md:hidden');
      $(this).next('div').removeClass('md:hidden');
    });

    $(selectors.btnLess).on('click', function(e) {
      $(this).parent('div').addClass('md:hidden');
      $(this).parent('div').prev('a').show();
      $(this).parent('div').prev('a').prev('div').removeClass('md:hidden');
    });
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
  Truncate.init();
});

