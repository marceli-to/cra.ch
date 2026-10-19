import { confirm } from '@/lib/confirm';

/**
 * Asks before deleting (the confirm dialog); resolves true or false.
 */
export function confirmDelete(title = 'Eintrag löschen?', options = {}) {
  return confirm({ title, ...options });
}

/**
 * Sets each item's order to its index and returns the list.
 */
export function withOrder(items) {
  items.forEach((item, index) => item.order = index);
  return items;
}

/**
 * Plain text of an HTML string (tags out, entities decoded), cut to length
 * (list labels). DOMParser doesn't run the HTML's scripts or handlers.
 */
export function truncate(html, length = 100) {
  const text = new DOMParser().parseFromString(html ?? '', 'text/html').body.textContent.replace(/\s+/g, ' ').trim();
  return text.length > length ? `${text.slice(0, length)}…` : text;
}
