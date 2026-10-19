// Teasers in a grid slot: the slot's aspect ratio sets the height, so the
// text is cut to the lines that fit above the "Mehr" link, ending in "…".
// On small screens the slot grows with its text and nothing is cut.
const selector = '[data-teaser-text]';
const clamped = 'is-clamped';

// Lines whose bottom lies within the box (paragraph spacing included)
function linesThatFit(text) {
  const bottom = text.getBoundingClientRect().bottom + 0.5;
  const range = document.createRange();
  range.selectNodeContents(text);
  const tops = new Set();
  for (const rect of range.getClientRects()) {
    if (rect.height && rect.bottom <= bottom) tops.add(Math.round(rect.top));
  }
  return tops.size;
}

function fit(text) {
  text.classList.remove(clamped);
  text.style.removeProperty('--teaser-lines');

  if (text.scrollHeight <= text.clientHeight + 1) return;

  text.style.setProperty('--teaser-lines', Math.max(1, linesThatFit(text)));
  text.classList.add(clamped);
}

export function init() {
  const texts = document.querySelectorAll(selector);
  if (!texts.length) return;

  const observer = new ResizeObserver((entries) => entries.forEach((entry) => fit(entry.target.querySelector(selector))));
  texts.forEach((text) => observer.observe(text.parentElement));

  // The webfont changes the line breaks
  document.fonts?.ready.then(() => texts.forEach(fit));
}
