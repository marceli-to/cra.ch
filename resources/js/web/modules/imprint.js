(function () {

  const classes = {
    hidden: 'is-hidden',
  };

  const selectors = {
    imprint: '[data-imprint]',
    btnImprint: '[data-btn-imprint]',
  };

  const init = () => {
    // toggle imprint on button click
    const btnImprint = document.querySelector(selectors.btnImprint);
    if (btnImprint) {
      btnImprint.addEventListener("click", toggle, false);
    }
  };


  const toggle = function(){
    const imprint = document.querySelector(selectors.imprint);
    imprint.classList.toggle(classes.hidden);
  };

  init();
  
})();