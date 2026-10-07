// <x-truncated-text>: preview, "mehr" link, then the full text with a
// "weniger" link at its end
const selectors = {
  btnMore: '[data-more]',
  btnLess: '[data-less]',
};

const hidden = 'is-hidden';

const isDiv = (el) => el?.tagName === 'DIV';

export function init() {
  document.querySelectorAll(selectors.btnMore).forEach((btn) => {
    btn.addEventListener('click', () => {
      btn.style.display = 'none';
      if (isDiv(btn.previousElementSibling)) btn.previousElementSibling.classList.add(hidden);
      if (isDiv(btn.nextElementSibling)) btn.nextElementSibling.classList.remove(hidden);
    });
  });

  document.querySelectorAll(selectors.btnLess).forEach((btn) => {
    btn.addEventListener('click', () => {
      const full = btn.parentElement;
      if (!isDiv(full)) return;
      full.classList.add(hidden);
      const more = full.previousElementSibling;
      if (more?.tagName !== 'A') return;
      more.style.display = '';
      if (isDiv(more.previousElementSibling)) more.previousElementSibling.classList.remove(hidden);
    });
  });
}
