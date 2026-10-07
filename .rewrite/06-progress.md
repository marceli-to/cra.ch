# Progress

## Where things stand

2026-10-07: survey written. `marceli-to/wiretap` removed (package,
`config/wiretap.php`, the `report()` hook in `Exceptions/Handler.php`,
`WIRETAP_*` from the local `.env`). Branch to use:
`rework/laravel-13-vue-3`.

Decisions 2026-10-07: PHP 8.3–8.5 on production, replace `laravel/ui`,
Tiptap, admin refresh yes, deploy as oxid, wiretap removed.

2026-10-07: production snapshot in `.rewrite/data/`, loaded into
`cristinarutz_prod`. Local `master` was 16 commits behind `origin/master`
(Team CMS, category order); fast-forwarded to `e22f376`, which matches
production's 31 migrations. Inventory re-measured.

2026-10-07: branch `rework/laravel-13-vue-3`. Images: `images:resize`
command and stricter uploads (`05`). Ran on the local copy of production:
34 files resized, uploads 1.1 GB → 940M; crops verified.

Local setup: `storage/app/public` holds a copy of the production snapshot,
and the resized result. Use `DB_DATABASE=cristinarutz_prod` for commands
and `artisan serve` against production data.

2026-10-07: `02` step 1 done — dead code removed (see "Backend").
Smoke test against `cristinarutz_prod`: all public pages 200, 21/21
project pages 200, 30/30 image URLs on a project page 200; public JS and
CSS bundles byte-identical, admin bundle only lost lodash.

2026-10-07: `02` step 2 done — Laravel 13.35 on the old skeleton, with
image-cache replaced by Glide in the same step (image-cache only allows
Laravel ≤ 11). See "Step 2" below.

2026-10-07: `02` step 3 done — slim skeleton, config trimmed to what
differs from Laravel 13's defaults. See "Step 3" below.

Next: `02` step 4 (own login instead of `laravel/ui`).

## Backend

### Step 1: dead code (2026-10-07)

Removed, each checked for references (PHP, Blade, JS, Sass, and the
production SQL for asset paths):

- PHP: `WorklistController` (no route, no view), `Mail/SubscriberVerifyEmail`
  (references a `Subscriber` model that doesn't exist), `Tasks/StorageCleanup`
  (never scheduled; Console Kernel imported a non-existent
  `App\Tasks\Newsletter`), `BroadcastServiceProvider` + `routes/channels.php`,
  `Auth/RegisterController` (registration is off), `View/Components/TextArea`,
  `server.php`, `config/imagecache.php`.
- Composer: `unsplash/unsplash`, `doctrine/dbal` (`->change()` is native
  since Laravel 11).
- npm: `bootstrap`, `popper.js`, `@fancyapps/ui`, `cleave.js`, `moment`,
  `vue-moment`, `nth-check`, `raw-loader`, `vue-the-mask`,
  `vuejs-datepicker`, `lodash` (`window._` was never used); `yarn.lock`
  (npm is used; `package-lock.json` is newer).
- Files: `public/js/app.js`, `public/assets/mix-manifest.json` (stale, from
  another project), `public/assets/media/*` (3 jpgs), 13 unused admin icons,
  SFMono fonts, `components/grid-item` and `text-area` views,
  `web/vendor/{scrollTo,debounce}.js`, `cms/mixins/DateTime.js`,
  `ui/{LabelInfo,Collapsible}.vue`, `_simplebar.scss`, `_homepage.scss`.
- Tests: the two stock `ExampleTest`s (the feature one failed on an empty
  DB) and the then-empty Unit suite in `phpunit.xml`.

Left for later steps: the other `Auth/*` controllers and views and the
`alert`/`button`/`text-field` components (step 4, own login),
`EventServiceProvider` (imports a non-existent listener; goes with the slim
skeleton, step 3), both TinyMCE folders (Tiptap), `composer.phar` (check
whether the server needs it).

### Step 2: Laravel 13 + Glide (2026-10-07)

- composer: PHP `^8.3` (platform pinned to 8.3.0), `laravel/framework`
  13.35, sanctum 4.3, tinker 3, ui 4.6 (until step 4), log-viewer 3.24,
  model-flags 1.5, sluggable 4, `intervention/image` 4.3, `league/glide`
  4.1, phpunit 12, collision 8.9, ignition 2.12, Carbon 3 (via the
  framework). Dropped `marceli-to/image-cache`, `intervention/image-laravel`
  (and its provider + the dead v2 `Image` facade alias), explicit `guzzle`
  and `nesbot/carbon`; `config/image.php`, `config/image-cache.php`.
  **`composer audit`: 0 advisories (was 42).**
- Intervention 4: `read()` → `decodePath()` in `ImageResizer`.
- The old skeleton (kernels, providers, `Handler`) still boots on 13;
  config/route/view caching all work.
- `App\Http\Controllers\ImageController` + `App\Support\Glide` keep the
  image-cache URLs and rules: `/img/original/{file}`, `/img/thumbnail/{file}`
  (300×300 cover), `/img/crop/{file}/{maxSize?}/{coords?}/{ratio?}` (crop,
  clamped to the image, else centre-crop to ratio; longer side scaled down to
  maxSize, default 2400, max 2600; never upscales; jpg/png at q75 as
  Intervention's default). Unknown sizes/coords/templates are 404s.
  Coords may be decimals (the admin sends the stored values; image-cache
  answered those with 400, so cropped previews in the admin were broken).
  Cache in `storage/app/.glide-cache`; `Glide::forget()` on re-crop, delete
  and `images:resize` (guarded against `..`).
- **Verified against production:** `.rewrite/tools/image-cache-match.py`
  maps 1,047 of the 1,049 cached production crops to the URL that made them
  (by image-cache's md5 of the params); `glide-compare.php` renders those plus
  the 333 thumbnails from the untouched snapshot. **1,380/1,380 same
  dimensions, 0 errors**, RMSE median 0.005, max 0.03 (a thumbnail;
  same framing on inspection — production rendered with GD, local Glide
  with Imagick). EXIF-rotated photos come out upright, as before.
- Smoke test (prod data, local copy): all pages 200, 21/21 projects,
  **581/581 image URLs** used on the site 200.
- Tests: `ImageRenditionTest` (8), re-crop clears the cache (1): 23 total.
- After go-live: delete `storage/app/public/cache/` (image-cache's 249 MB).

### Step 3: slim skeleton (2026-10-07)

- `bootstrap/app.php` (`withRouting` / `withMiddleware` / `withExceptions`),
  `bootstrap/providers.php` (only `AppServiceProvider`), `public/index.php`
  and `artisan` as in Laravel 13. Deleted: both kernels, `Exceptions/Handler`,
  `Auth`/`Event`/`RouteServiceProvider`, and eight middleware classes that
  only restated the framework's (`CheckRole` stays, alias `role`).
- Carried over in `bootstrap/app.php`: `api` group in the old order
  (Sanctum stateful → `throttle:200,1` → bindings); CSRF exemption for
  `api/image/upload` and `api/file/upload` (Dropzone sends no token);
  logged-in users on guest pages go to `/administration` (admins) or `/`;
  `api/*` always answers JSON. `RouteServiceProvider::HOME/DASHBOARD` in the
  `Auth/*` controllers became literals.
- Config: 19 files → 6. Kept only the differences: `app` (locale `de`,
  `AppHelper`/`DateHelper` aliases), `auth` (reset table `password_resets`),
  `filesystems` (local root `storage/app`, not Laravel 13's
  `storage/app/private`), plus app-only `images`, `pages`, `seo`. Dropped
  incl. `sanctum` (package defaults; the old file listed other projects'
  domains), `cors` (same-origin; listed `/herkulesdesign/csrf-cookie`),
  `client` (unused since the dead mail class went).
- Verified against the previous commit in a separate worktree:
  `route:list` 129 = 129 routes, only App→framework middleware class names
  changed; global middleware and groups compared; over HTTP on both trees
  with prod data: CSRF (419 without token, uploads exempt), full admin login
  → admin → `/api/user` → `/api/projects` → logout with a temporary admin
  user (removed again) — identical results. Public pages, 21 projects,
  images, 404 all fine. Tests: `MiddlewareTest` (5) → 28 total.
- Note: CSRF is skipped under PHPUnit, so the exemption is only checked
  over HTTP. bcrypt rounds go 10 → 12; existing passwords keep working and
  are rehashed on next login.

### Found on the way: gallery with an empty first slot (live bug)

`/projekt/anlage-untere-vogelsangstrasse` returns **500 on production**
(checked 2026-10-07): grid 387 (`1sq-1_1`) has items in slots 1 and 2 but
not 0, and the gallery partials read `$items[n]` by offset, not by slot.
Fixed on this branch in `components/galleries/gallery.blade.php` (items
keyed by `position`, null for empty slots). Every other grid has gap-free
positions in id order, and their HTML is unchanged (21 pages + home +
diary compared). On production until go-live: fill slot 1 of that grid in
the admin, or remove the row.

## Images

## Admin

## Public site JS

## Tests

## Deploy notes

- **Production `.env` for Laravel 13** (config files now fall back to the
  framework's defaults, which changed): set `DB_CONNECTION=mysql`,
  `SESSION_DRIVER` (cookie, as locally — default is now `database`),
  `CACHE_STORE=file` (was `CACHE_DRIVER`; default is now `database`),
  `QUEUE_CONNECTION=sync`, `MAIL_MAILER=smtp`, `LOG_STACK=single,slack` and
  `LOG_LEVEL=debug` (what the old logging config hard-coded),
  `SANCTUM_STATEFUL_DOMAINS` with the production host(s), `APP_URL` the
  exact origin. Remove `CACHE_DRIVER`, `MAIL_DRIVER`, `BROADCAST_DRIVER`,
  `PUSHER_*`, `MIX_*`. (Local `.env` already done, 2026-10-07.)
- The image work (`images:resize`, stricter uploads) ships with the rework,
  not separately: `master` stays as it is until go-live (decided
  2026-10-07). On the server, after `composer install` and before
  `images:warm`: `php artisan images:resize --dry-run`, then
  `php artisan images:resize`. Check `upload_max_filesize`/`post_max_size`
  ≥ 30M and Imagick first (`05`).
