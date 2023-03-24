(function () {

  const classes = {
    visible: 'is-visible',
    active: 'is-active',
  };

  const selectors = {
    menu: '.js-menu',
    btnMenu: '.js-menu-btn',
  };

  const init = () => {
    const btnMenu = document.querySelector(selectors.btnMenu);
    
    if (btnMenu) {
      btnMenu.addEventListener("click", toggle, false);
    }

  };

  const toggle = function(){
    const menu = document.querySelector(selectors.menu);
    menu.classList.toggle(classes.visible);
    const btnMenu = document.querySelector(selectors.btnMenu);
    btnMenu.classList.toggle(classes.active);
  };

  init();
  
})();