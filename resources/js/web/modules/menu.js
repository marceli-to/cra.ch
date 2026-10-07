const classes = {
  visible: 'is-visible',
  block: 'is-block',
  active: 'is-active',
  hidden: 'is-hidden',
};

const selectors = {
  menu: '[data-menu]',
  browse: '[data-browse]',
  btnMenu: '[data-menu-btn]',
  btnMenuItemParent: '[data-menu-parent]',
};

const toggle = () => {
  document.querySelector(selectors.btnMenu).classList.toggle(classes.active);

  const menu = document.querySelector(selectors.menu);
  menu.classList.toggle(classes.visible);

  document.querySelector(selectors.browse)?.classList.toggle(classes.hidden);

  if (!menu.classList.contains(classes.visible)) {
    hideAllItems();
  }
};

const toggleItems = (btn) => {
  const active = btn.classList.toggle(classes.active);
  btn.nextElementSibling.classList.toggle(classes.block, active);
};

const hideAllItems = () => {
  document.querySelectorAll(`${selectors.btnMenuItemParent} + ul`).forEach((ul) => {
    ul.previousElementSibling.classList.remove(classes.active);
    ul.classList.remove(classes.block);
  });
};

export function init() {
  document.querySelector(selectors.btnMenu)?.addEventListener('click', toggle);
  document.querySelectorAll(selectors.btnMenuItemParent).forEach((btn) => {
    btn.addEventListener('click', () => toggleItems(btn));
  });
}
