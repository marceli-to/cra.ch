# Public site QA (Playwright)

Run from a scratch dir with `npm i playwright` (e.g. `/tmp/cr-qa`), against
the PHP server on prod data at `http://127.0.0.1:8765` (see `06-progress.md`,
Gotchas).

- `shots.js <outdir>` — full-page screenshots of the main pages and 6
  projects at 1440 and 390 px; logs console errors and 4xx/5xx. Compare two
  runs with ImageMagick `compare -metric AE`.
- `styles.js <path>` — computed style of every element with the Vite CSS vs
  the old `/assets/css/app.css` (needs that file; it was deleted with the
  Vite switch, restore it from `cce6764` to compare again).
- `interact.js vite|mix` — menu, mehr/weniger, project info, lightbox,
  imprint. `mix` swaps in the old `/assets/js/app.js` (same note).
- `lazy.js vite|mix [path]` — how many lazy images load after scrolling.
