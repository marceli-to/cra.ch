const selectors = {
  imprint: '[data-imprint]',
  btnImprint: '[data-btn-imprint]',
};

export function init() {
  document.querySelector(selectors.btnImprint)?.addEventListener('click', () => {
    document.querySelector(selectors.imprint).classList.toggle('is-hidden');
  });
}
