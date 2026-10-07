# Public site JS: jQuery → vanilla ES modules

*Done 2026-10-07; details and verification in `06-progress.md`, "Public
site JS". Instead of `@fancyapps/ui` v5: an own lightbox with fancyBox 3's
look (no licence question). Lazy loading stays vanilla-lazyload (npm), as
`<x-image>` emits `data-srcset`.*

Separate from the rework, as in oxid (`08-frontend-js.md` there): shares only
the Vite migration and can be done before, after, or not at all.

## Today

Only `fancybox.js` and `truncate.js` use jQuery. `web/app.js` requires `bootstrap.js` (global jQuery) and 8 modules:

| Module | Lines | jQuery |
|---|---|---|
| `menu.js` | 61 | no |
| `fancybox.js` | 60 | yes (fancyBox v3) |
| `project.js` | 52 | no |
| `truncate.js` | 43 | yes |
| `imprint.js` | 22 | no |
| `touch.js` | 22 | no |
| `lazy.js` | 6 | lazyload vendor |
| `vhcheck.js` | 2 | vendor |

Vendored: `fancybox.js` (v3.5.7, jQuery, GPLv3/commercial), `lazyload.js`,
`scrollTo.js`, `debounce.js`, `vhcheck.js`.

## Target (oxid `ccbfa36`, `046a170`)

- ES modules, `let`/`const`, `data-` hooks instead of `js-` classes.
- jQuery, `bootstrap`, `popper.js`, `lodash` out of package.json.
- Gallery: **`@fancyapps/ui` v5** (already in package.json, unused) instead
  of vendored v3 — note v5 is also GPLv3/commercial, same as today. Or
  oxid's own lightbox if it has one that fits.
- `vhcheck` → CSS `dvh`/`svh` units; `scrollTo` → `scrollIntoView` /
  `scroll-behavior: smooth`; `debounce` → small local helper.
- Lazy loading: native `loading="lazy"` if `<x-image>` emits real sizes.
- Google tag only in production (oxid `cbe136c`), if present.

Playwright smoke checks per page (menu, gallery, project page, imprint).
