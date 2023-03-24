(function () {

  const classes = {
    touched: 'is-touched',
  };

  const selectors = {
    // menu items
    menuItems: '.js-menu li a',

    // generic items
    globalItems: '[data-touch]',
  };

  const init = () => {
    const menuItems = document.querySelectorAll(selectors.menuItems);
    const menuItemsArray = [].slice.call(menuItems);

    const globalItems = document.querySelectorAll(selectors.globalItems);
    const globalItemsArray = [].slice.call(globalItems);

    const itemsArray = [...menuItemsArray, ...globalItemsArray];

    itemsArray.forEach(function (item) {
      item.addEventListener("touchstart", function() {
        this.classList.add(classes.touched);
      }, false);
      item.addEventListener("touchend", function() {
        this.classList.remove(classes.touched);
      }, false);
    });
  };

  init();
  
})();