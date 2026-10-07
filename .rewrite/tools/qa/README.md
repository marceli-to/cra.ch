# Public site QA (Playwright)

Run from a scratch dir with `npm i playwright` (e.g. `/tmp/cr-qa`), against
the PHP server on prod data at `http://127.0.0.1:8765` (see `06-progress.md`,
Gotchas). A reference build is a directory with `app.css` and `app.js`,
e.g. from an older commit:
`git show <commit>:public/build/assets/app-<hash>.css > ref/app.css`.
Requests for the current `build/assets/app-*` files are swapped for it.

- `shots.js <outdir>` — full-page screenshots of the main pages and 6
  projects at 1440 and 390 px; logs console errors and 4xx/5xx. With
  `REF=<dir>` against the reference build. Compare two runs with
  ImageMagick `compare -metric AE`.
- `styles.js <path> <ref.css>` — computed style of every element, current
  CSS vs the reference CSS (JS off).
- `interact.js` — menu, mehr/weniger, project info and preview, lightbox
  (prev at first / next at last, click on and beside the image, Tab, Esc,
  focus and scroll position), imprint. Swap `chromium` for `webkit` to
  run it in WebKit.
- `lazy.js current|ref [path]` — how many lazy images load after
  scrolling (`ref` needs `REF=<dir>`).
- `lightbox-measure.js new|v3` — image, caption and button positions of
  the lightbox at 1440 and 390 px, keyboard, swipe (touch) and Esc; `v3`
  runs fancyBox 3 (`REF=<dir>` with the build from `7074658`). Screenshots
  to `OUT` (default `/tmp`).
