(function () {

  const classes = {
    active: 'is-active',
    visible: 'is-visible',
  };

  const selectors = {
    btnToggleInfo: '[data-btn-project-info]',
    wrapperInfo: '[data-project-info]',
  };

  const init = () => {
    // Get all buttons
    const btnToggleInfo = document.querySelectorAll(selectors.btnToggleInfo);

    // Add event listener to all buttons to toggle info
    btnToggleInfo.forEach((btn) => {
      btn.addEventListener('click', function() {
        toggleInfo(btn);
      }, false);
    });
  };

  const toggleInfo = function(btn){
    btn.classList.toggle(classes.active);
    const wrapperInfo = document.querySelector(selectors.wrapperInfo);
    wrapperInfo.classList.toggle(classes.visible);
  };

  init();
  
})();