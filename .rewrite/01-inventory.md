# Inventory (measured 2026-10-07, `e22f376`)

*First measured on `e6c0bae`, which turned out to be 16 commits behind
`origin/master` (Team CMS, category sorting). Local `master` was
fast-forwarded and the admin numbers below re-measured.*

## Production snapshot (2026-10-07)

In `.rewrite/data/` (gitignored, 1.3 GB): `cristin4_cristina.sql`
(phpMyAdmin dump; **Hostpoint**, MariaDB 10.11.19, phpMyAdmin on PHP 8.3)
and `public/` = `storage/app/public`. Loaded locally into the scratch DB
**`cristinarutz_prod`** (MySQL 5.7 on 127.0.0.1:3306); the regular
`cristinarutz` DB is untouched.

- 31 migrations ran on production = the 31 in the repo at `e22f376`.
- Rows: 29 projects, 4 categories, 32 category links, 138 grids,
  365 grid items, 6 articles, 16 resumes, 2 team members, 63 flags,
  1 each of about/contact/diary/home/service, 3 users (all admin, all
  email-verified), 0 files.
- Images: 751 rows, **384 live**, 367 soft-deleted. Live by owner:
  Project 602 / Diary 33 / About 12 / Service 12 / Contact 7 / Article 1 /
  none 84 (rows incl. deleted). Max `order` 13 (TINYINT is fine here).
- `uploads/`: 448 files (432 jpg, 16 png), 1.1 GB. Every live image row has
  its file. **64 files have no image row at all** — cleanup candidates.
- Originals are big: median 3000 px wide, 125 wider than 4000 px,
  23 over 10 MB, **29 over 40 megapixels (28 of them live)**, largest
  8896×14192 (126 MP). See `05`.
- `cache/`: 1,049 crop + 333 thumbnail renditions, 249 MB.

## Backend

- `laravel/framework` **v11.44.2**, PHP `^8.2` in composer.json; local CLI 8.4.
- **Old skeleton:** `app/Http/Kernel.php`, `app/Console/Kernel.php`,
  `app/Exceptions/Handler.php`, five providers (`App`, `Auth`, `Broadcast`,
  `Event`, `Route`), `bootstrap/app.php` with kernel bindings,
  `server.php`, 9 middleware classes in `app/Http/Middleware`
  (incl. own `CheckRole` → `role:admin`).
- Config: 23 files, incl. `image-cache.php` **and** `imagecache.php`
  (the latter is the old intervention/imagecache config — dead),
  `client.php`, `pages.php`, `seo.php`, `broadcasting.php`.
- 18 models (incl. `TeamMember`), 9 public controllers, 17 API controllers, 6 `Auth/*`
  controllers from `laravel/ui`, `app/Services/Media.php` (Intervention v3, GD).
- 31 migrations. ~100 `Route::` lines in `routes/api.php`, all under
  `auth:sanctum`.
- Auth: `Auth::routes(['verify' => true, 'register' => false])`, Blade login
  views, `AuthenticatesUsers`; admin SPA at `/administration/{any?}` behind
  `auth:sanctum`, `verified`, `role:admin`. **Already session-based Sanctum.**
- Tests: only `ExampleTest` (Feature + Unit).

### composer.json packages

| Package | Used where | Note |
|---|---|---|
| `doctrine/dbal ^3.6` | — | not needed since Laravel 11 column changes |
| `guzzlehttp/guzzle` | framework | advisories, update with the rest |
| `intervention/image` + `-laravel` | `Services/Media.php`, `config/image.php` | v4 of `-laravel` supports 13 |
| `laravel/sanctum ^4` | auth | v4.3 supports 13 |
| `laravel/tinker ^2.9` | — | → `^3` |
| `laravel/ui ^4.3` | `Auth::routes`, `AuthenticatesUsers` | v4.6 supports 13 |
| `marceli-to/image-cache` | `/img/crop`, `/img/thumbnail`, `/img/original`, `ImageController::destroy` | **replace with Glide** |
| ~~`marceli-to/wiretap ^1.4`~~ | ~~`Exceptions/Handler.php`~~ | **removed 2026-10-07** |
| `nesbot/carbon ^2` | — | Laravel 13 needs Carbon 3 |
| `opcodesio/log-viewer ^3` | log viewer | v3.24 supports 13 |
| `spatie/laravel-model-flags` | 7 models (`HasFlags`) | 1.5 supports 13 |
| `spatie/laravel-sluggable ^3.4` | `Category` (+ `project:slug` binding) | → `^4` |
| `unsplash/unsplash ^3.2` | **no use found** in `app/`, `resources/` | delete |
| `spatie/laravel-ignition`, `nunomaduro/collision ^8`, `phpunit ^10` | dev | phpunit has an advisory → 11/12 |

## Images

- Uploads in `storage/app/public/uploads`, cache in `storage/app/public/cache`
  (both empty locally — **need a production snapshot**, see `04` #1).
- Public markup: one component, `resources/views/components/image.blade.php`
  (`<picture>` with `data-srcset` per breakpoint, lazysizes-style),
  plus `galleries/gallery-link-image.blade.php` (fancyBox link, 2000px) and
  the project OG image (1500px). All use
  `/img/crop/{name}/{maxSize}/{coords}`.
- Admin: `modules/images/mixins/utils.js` `getSource()` uses
  `/img/thumbnail/{name}`, `/img/original/{name}`, `/img/crop/...`.
- Static: `/assets/img/og/*.jpg`, `placeholder.png`, `transparent.png`.

## Build

- Laravel Mix 6: `web/app.js` + `web/app.scss`, `cms/app.js` + `cms/app.scss`,
  `@` → `resources/js/cms/`. ~10.2k lines of Sass.
- TinyMCE **7.2.0** self-hosted in `public/assets/js/cms/tinymce`, plus an
  older `public/assets/js/cms/_tinymce` (custom skin referenced from
  `config/tiny.js`) — clean up.

## Admin (resources/js/cms)

- 78 `.vue` files (117 incl. `.js`), ~7.9k LOC in `.vue`. Largest:
  `modules/grid/Index.vue` 1,150, `modules/images/components/Edit.vue` 315,
  `views/pages/project/project/Form.vue` 282, `modules/grid/components/GridImageSelector.vue` 272.
- Modules: `files`, `galleries`, `grid`, `images`, `links`, `videos`.
  Pages: home (+article), project (+category), diary, service, contact,
  about, team (+resume).
- Vue 2 smells: 35 `mixins:`, 20 `$parent` (8 files, all images/files
  modules), 9 `.sync` (8 Forms), 1 `Vue.filter('truncate')`,
  1 `beforeDestroy` (`mixins/ErrorHandling.js`), 3 `$store` (App.vue only),
  55 `$notify` in 29 files. vuedraggable 7 files, TinyMCE 8 Forms +
  config, `vue-feather-icons` 35 files. None: `slot-scope`, `$listeners`, `$on`, `$set`.

## Public site JS (resources/js/web)

- `bootstrap.js` sets global jQuery. 8 modules (`menu`, `lazy`, `vhcheck`,
  `touch`, `fancybox`, `project`, `truncate`, `imprint`; 2–61 lines each),
  vendored `fancybox.js` (**fancyBox v3.5.7**, jQuery), `lazyload.js`,
  `scrollTo.js`, `debounce.js`, `vhcheck.js`.
- `@fancyapps/ui ^5` is in package.json but **not imported anywhere**.
- `bootstrap`, `popper.js`, `lodash` in package.json, not referenced from Sass/JS.
