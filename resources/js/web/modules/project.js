(function () {

  const classes = {
    visible: 'is-visible',
    active: 'is-active',
  };

  const selectors = {
    listItem: 'li.is-visible a[data-project]',
    listItemFirst: 'li.is-visible:first-child a[data-project]',
    imageItem:  'picture[data-project]',

    btnInfo: '.js-btn-project-info',
    wrapperInfo: '.js-project-info'
  };

  const init = () => {

    // Mouse over project list
    const listItems = document.querySelectorAll(selectors.listItem);
    const listItemFirst = listItems[0];
    const listItemsArray = [].slice.call(listItems);
    const imageItems = document.querySelectorAll(selectors.imageItem);
    const imageItemsArray = [].slice.call(imageItems);

    if ("ontouchstart" in document.documentElement == false)
    {
      listItemsArray.forEach(function (item) {

        item.addEventListener("mouseover", function() {
          const id = item.dataset.project;
          imageItemsArray.forEach(function (image) {
            image.classList.add('is-hidden');
          });
          const targetImage = document.querySelector('picture[data-project="'+ id +'"]');
          targetImage.classList.remove('is-hidden');
        });
  
        item.addEventListener("mouseleave", function() {
          imageItemsArray.forEach(function (image) {
            image.classList.add('is-hidden');
          });
  
          // const id = listItemFirst.dataset.project;
          const targetImage = document.querySelector('picture[data-project="preview"]');
          targetImage.classList.remove('is-hidden');
        });
  
      });
    }

    // Toggle info
    const btnInfo = document.querySelector(selectors.btnInfo);
    if (btnInfo) {
      btnInfo.addEventListener("click", toggleInfo, false);
    }
  };

  const toggleInfo = function(){
    const wrapperInfo = document.querySelector(selectors.wrapperInfo);
    wrapperInfo.classList.toggle(classes.visible);
  };

  init();
  
})();