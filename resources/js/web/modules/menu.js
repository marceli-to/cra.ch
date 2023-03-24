var Menu = (function() {
	
	// selectors
	var selectors = {
    html:              'html',
    body:              'body',
    header:            '.js-site-header',
    menu:              '.js-menu',
    menuBtn:           '.js-menu-btn',
    menuBtnToggle:     '.js-menu-btn-toggle',
    menuBtnNewsletter: '.js-menu-btn-newsletter',

	};

  // css classes
  var classes = {
    active:   'is-active',
    visible:  'is-visible',
    hidden:   'is-hidden',
    open:     'is-open',
    hasMenu:  'has-menu',
  };

  // Init
  var _initialize = function() {
    _bind();

    // check for hash
    let hash = window.location.hash;
    if (hash) {
      hash = hash.substring(1,hash.length);
      _jumpTo(hash);
    }
  };

  // Bind events
  var _bind = function() {
    $(selectors.body).on('click', selectors.menuBtn, function(){
      _toggle($(this));
    });

    $(selectors.body).on('click', selectors.menuBtnToggle, function(){
      _toggleSub($(this));
    });

    $(selectors.body).on('click', selectors.menuBtnNewsletter, function(){
      _toggleForm($(this));
    });

    $(window).on('hashchange', function(e) {
      let hash = window.location.hash;
      if (hash) {
        hash = hash.substring(1,hash.length);
        _jumpTo(hash);
      }
    });
  };

  var _toggle = function() {
    $(selectors.menu).toggleClass(classes.visible);
    $(selectors.menuBtn).toggleClass(classes.active);
    $(selectors.header).toggleClass(classes.hasMenu);
  };

  var _toggleSub = function(btn) {
    btn.next('ul').toggle();
  };

  var _toggleForm = function(btn) {
    btn.toggleClass(classes.active);
    btn.next('div').toggle();
  };

  var _hide = function() {
    $(selectors.menu).removeClass(classes.visible);
    $(selectors.menuBtn).removeClass(classes.active);
    $(selectors.header).removeClass(classes.hasMenu);
  };

  var _jumpTo = function(hash) {
    var el = document.querySelector(`[data-id="${hash}"]`);
    _hide();
    window.scrollTo({
      top: el.offsetTop - 70,
      left: 0,
      behavior: 'smooth'
    });
  };

  var _activate = function(hash) {
    if (hash.length) {
      const current = document.querySelector('.js-menu a.is-active:not(.js-menu-btn-toggle)');
      if (current) {
        current.classList.remove(classes.active);
      }
      const selected = document.querySelector('.js-menu a[href*="'+hash+'"]');
      if (selected) {
        selected.classList.add(classes.active);
      }
    }
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
  Menu.init();
});

