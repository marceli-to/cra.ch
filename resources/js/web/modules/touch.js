(function () {

  const classes = {
    touched: 'is-touched',
  };

  const selectors = {
    touchItems: '[data-touch]',
  };

  const init = () => {
    const touchItems = document.querySelectorAll(selectors.globalItems);
    const touchItemsArray = [].slice.call(touchItems);

    touchItemsArray.forEach(function (item) {
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