// Full-screen gallery for [data-lightbox] links: every link on the page is
// one group, in document order. Replaces fancyBox 3 with the same look.

const selectors = {
  item: '[data-lightbox]',
};

const classes = {
  open: 'is-open',
  current: 'is-current',
  inactive: 'is-inactive',
  locked: 'has-lightbox',
};

// Opening fades in like fancyBox's `animationDuration`; keep in sync with
// the transitions in components/_lightbox.scss
const fadeDuration = 366;
const slideDuration = 600;
const swipeThreshold = 50;

let items = [];
let index = 0;
let el = null;
let lastFocus = null;
let swiped = false;
let closing = null;

export function init() {
  document.addEventListener('click', (event) => {
    const link = event.target.closest(selectors.item);
    if (!link) return;
    event.preventDefault();
    items = [...document.querySelectorAll(selectors.item)];
    open(items.indexOf(link));
  });
}

const build = () => {
  el = document.createElement('div');
  el.className = 'lightbox';
  el.hidden = true;
  el.setAttribute('role', 'dialog');
  el.setAttribute('aria-modal', 'true');
  el.setAttribute('aria-label', 'Bildergalerie');
  el.innerHTML = `
    <div class="lightbox__main">
      <div class="lightbox__stage"></div>
      <button type="button" class="lightbox__prev" aria-label="Vorheriges Bild"></button>
      <button type="button" class="lightbox__next" aria-label="Nächstes Bild"></button>
    </div>
    <div class="lightbox__caption" aria-live="polite"><div></div><span></span></div>
    <button type="button" class="lightbox__close" aria-label="Schliessen"></button>`;
  document.body.appendChild(el);

  el.querySelector('.lightbox__close').addEventListener('click', close);
  el.querySelector('.lightbox__prev').addEventListener('click', () => show(index - 1));
  el.querySelector('.lightbox__next').addEventListener('click', () => show(index + 1));

  // A click beside the image closes, as fancyBox's `clickSlide: 'close'`
  const main = el.querySelector('.lightbox__main');
  main.addEventListener('click', (event) => {
    const beside = event.target === main || event.target.matches('.lightbox__stage');
    if (beside && !swiped) close();
  });

  let start = null;
  el.addEventListener('pointerdown', (event) => {
    swiped = false;
    start = { x: event.clientX, y: event.clientY };
  });
  el.addEventListener('pointerup', (event) => {
    if (!start) return;
    const dx = event.clientX - start.x;
    const dy = event.clientY - start.y;
    start = null;
    if (Math.abs(dx) > swipeThreshold && Math.abs(dx) > Math.abs(dy)) {
      swiped = true;
      show(index + (dx < 0 ? 1 : -1));
    }
  });
  el.addEventListener('pointercancel', () => { start = null; });
};

const open = (i) => {
  if (!el) build();
  clearTimeout(closing);
  lastFocus = document.activeElement;
  el.hidden = false;
  document.documentElement.classList.add(classes.locked);
  document.addEventListener('keydown', onKeydown);
  show(i, true);
  // Next frame, so the opacity transition runs
  requestAnimationFrame(() => el.classList.add(classes.open));
  el.querySelector('.lightbox__close').focus({ preventScroll: true });
};

const close = () => {
  el.classList.remove(classes.open);
  document.removeEventListener('keydown', onKeydown);
  closing = setTimeout(() => {
    el.hidden = true;
    el.querySelector('.lightbox__stage').replaceChildren();
    document.documentElement.classList.remove(classes.locked);
    lastFocus?.focus({ preventScroll: true });
  }, fadeDuration);
};

const show = (i, immediate = false) => {
  if (i < 0 || i >= items.length || (i === index && !immediate)) return;
  index = i;
  const link = items[i];
  const caption = link.dataset.caption || '';

  const stage = el.querySelector('.lightbox__stage');
  const previous = [...stage.children];
  const img = document.createElement('img');
  img.alt = caption;
  img.draggable = false;
  img.src = link.href;
  stage.appendChild(img);

  const reveal = () => {
    if (index !== i) return;
    img.classList.add(classes.current);
    previous.forEach((p) => p.classList.remove(classes.current));
    setTimeout(() => previous.forEach((p) => p.remove()), immediate ? 0 : slideDuration);
  };
  img.decode().then(reveal, reveal);

  el.querySelector('.lightbox__caption div').textContent = caption;
  el.querySelector('.lightbox__caption span').textContent = `${i + 1}/${items.length}`;
  el.querySelector('.lightbox__prev').classList.toggle(classes.inactive, i === 0);
  el.querySelector('.lightbox__next').classList.toggle(classes.inactive, i === items.length - 1);

  // Preload the neighbours
  [items[i - 1], items[i + 1]].forEach((n) => { if (n) new Image().src = n.href; });
};

const onKeydown = (event) => {
  if (event.key === 'Escape') close();
  else if (event.key === 'ArrowLeft') show(index - 1);
  else if (event.key === 'ArrowRight') show(index + 1);
  else if (event.key === 'Tab') {
    // Keep focus on the three buttons
    const buttons = [...el.querySelectorAll('button')];
    const at = buttons.indexOf(document.activeElement);
    const next = (at + (event.shiftKey ? -1 : 1) + buttons.length) % buttons.length;
    buttons[next].focus();
    event.preventDefault();
  }
};
