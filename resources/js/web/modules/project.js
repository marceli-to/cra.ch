(function () {

  const classes = {
    active: 'is-active',
    visible: 'is-visible',
  };

  const selectors = {
    btnToggleInfo: '[data-btn-project-info]',
    wrapperInfo: '[data-project-info]',
    browseBtn: '[data-browse-btn]',
    browsePreview: '[data-browse-preview]',
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

    // Get all browse buttons
    const browseBtn = document.querySelectorAll(selectors.browseBtn);

    // Add event listener (mouse over, mouse out) to all browse buttons
    browseBtn.forEach((btn) => {
      btn.addEventListener('mouseover', function() {
        showPreview(btn);
      }, false);
      btn.addEventListener('mouseout', function() {
        hidePreview(btn);
      }, false);
    });
  };

  const toggleInfo = function(btn){
    btn.classList.toggle(classes.active);
    const wrapperInfo = document.querySelector(selectors.wrapperInfo);
    wrapperInfo.classList.toggle(classes.visible);
  };

  const showPreview = function(btn){
    const preview = document.querySelector(selectors.browsePreview);
    preview.classList.add(classes.visible);
    preview.innerHTML = btn.title;
  };

  const hidePreview = function(btn){
    const preview = document.querySelector(selectors.browsePreview);
    preview.classList.remove(classes.visible);
    preview.innerHTML = '';
  };

  init();
  
})();