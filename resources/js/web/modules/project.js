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

const toggleInfo = (btn) => {
  btn.classList.toggle(classes.active);
  document.querySelector(selectors.wrapperInfo).classList.toggle(classes.visible);
};

// Hovering the previous/next links shows the project's title
const showPreview = (btn) => {
  const preview = document.querySelector(selectors.browsePreview);
  preview.classList.add(classes.visible);
  preview.textContent = btn.title;
};

const hidePreview = () => {
  const preview = document.querySelector(selectors.browsePreview);
  preview.classList.remove(classes.visible);
  preview.textContent = '';
};

export function init() {
  document.querySelectorAll(selectors.btnToggleInfo).forEach((btn) => {
    btn.addEventListener('click', () => toggleInfo(btn));
  });

  document.querySelectorAll(selectors.browseBtn).forEach((btn) => {
    btn.addEventListener('mouseover', () => showPreview(btn));
    btn.addEventListener('mouseout', hidePreview);
  });
}
