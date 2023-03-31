(function () {

  const classes = {
    visible: 'is-visible',
    block: 'is-block',
    active: 'is-active',
    hidden: 'is-hidden',
    current: 'is-current',
  };

  const selectors = {
    menu: '[data-menu]',
    browse: '[data-browse]',
    btnMenu: '[data-menu-btn]',
    btnMenuItemParent: '[data-menu-parent]',
  };

  const init = () => {

    const btnMenu = document.querySelector(selectors.btnMenu);
    if (btnMenu) {
      btnMenu.addEventListener("click", toggle, false);
    }

    const btnMenuItemParent = document.querySelectorAll(selectors.btnMenuItemParent);
    if (btnMenuItemParent) {
      btnMenuItemParent.forEach(function (btn) {
        btn.addEventListener("click", function(){
          toggleItems(btn);
        }, false);
      });
    }
  };

  const toggle = function(){
    const btnMenu = document.querySelector(selectors.btnMenu);
    btnMenu.classList.toggle(classes.active);

    const menu = document.querySelector(selectors.menu);
    menu.classList.toggle(classes.visible);

    const browse = document.querySelector(selectors.browse);
    if (browse) {
      browse.classList.toggle(classes.hidden);
    }

    if (!menu.classList.contains(classes.visible)) {
      hideAllItems();
    }
  };

  const toggleItems = function(btn){

    if (btn.classList.contains(classes.active)) {
      btn.classList.remove(classes.active);
      btn.nextElementSibling.classList.remove(classes.block);
      return;
    }
    const ul = btn.nextElementSibling;
    ul.classList.add(classes.block);
    btn.classList.add(classes.active);
  };

  const hideAllItems = function(){
    const uls = document.querySelectorAll(selectors.btnMenuItemParent + ' + ul');
    uls.forEach(function (ul) {
      ul.previousElementSibling.classList.remove(classes.active);
      ul.classList.remove(classes.block);
    });
  };

  init();
  
})();