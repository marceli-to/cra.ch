# Progress

## Where things stand (handover, 2026-10-07)

**Backend and images: done. Frontend: not started.**

- Branch `rework/laravel-13-vue-3`, local only (**not pushed**, no
  upstream), on top of `e22f376` (= `origin/master` =
  production). `master` stays untouched until go-live (user's decision);
  nothing from the branch is to be cherry-picked there.
- Laravel 13.35 on the slim skeleton, PHP `^8.3` (platform 8.3.0),
  `composer audit` clean, Glide images with signed URLs + AVIF/WebP, own
  login (`AuthController`), 48 PHPUnit tests (`php artisan test`).
- Admin is still **Vue 2 + Laravel Mix** (bundle rebuilt with
  `npm run production` after admin changes, built files committed). The
  public site JS is still jQuery + Mix.

### Next: the frontend

Order (see `03-frontend-vue3.md`, `07-frontend-js.md`, `08-admin-ui.md`):

1. Vite for the public site (`07`): oxid's `vite.config.js` with this
   site's entries (`resources/{sass,js}/web/app.*`), replace `mix()` in
   the layouts; then jQuery out (only `fancybox.js`, `truncate.js` use it),
   fancyBox v3 → `@fancyapps/ui` v5 (or oxid's lightbox), drop the vendored
   `vhcheck` (`scrollTo`/`debounce` are already gone). Keep `data-srcset` lazy loading working
   (`<x-image>` emits it; vanilla-lazyload in `resources/js/web/vendor`).
2. Admin to Vite + Vue 3 in one go (`03`): `<script setup>`, composables
   for the 35 mixins, oxid's uploader / notifications / SortableJS /
   Phosphor icons, vue-router 4, `lib/http.js`, Tiptap 3 instead of
   TinyMCE 7 (8 forms; run oxid's `tiptap-roundtrip.mjs` over the stored
   HTML first), cropper v2. Grid builder (`modules/grid/Index.vue`,
   1,150 LOC) last. Uploader must send the CSRF token, then drop the
   `validateCsrfTokens(except:)` for the two upload routes in
   `bootstrap/app.php`. Login could move into the SPA as in oxid (JSON
   `AuthController` there), or stay Blade.
3. Admin UI refresh (`08`, decided yes) while porting.

Copy building blocks from oxid
(`github.com/jamon-marcel/oxid.ch`, branch `rework/laravel-13-vue-3`;
a local clone is at `../oxid.ch`, use `git show origin/rework/laravel-13-vue-3:<path>`).

### Local environment

- `.rewrite/data/` (gitignored): production DB dump + `storage/app/public`
  snapshot (untouched originals).
- MySQL 5.7 on 127.0.0.1:3306 (DBngin; `mysql -uroot -h127.0.0.1 -P3306`):
  - `cristinarutz` — the old local DB (outdated schema, ignore)
  - `cristinarutz_prod` — production copy **after** a local
    `images:resize` run (34 originals scaled, coords scaled) and the
    width/height migration; matches `storage/app/public/uploads`
  - `cristinarutz_snapshot` — untouched production dump (for comparisons)
- `storage/app/public/` holds the production files (resized as above);
  `storage/app/originals/` the untouched copies of the 34 resized files;
  `storage/app/.glide-cache/` warm.
- Local `.env` already uses the Laravel 13 names (`CACHE_STORE`,
  `LOG_STACK=single,slack`, `LOG_LEVEL=debug`; Pusher/MIX/BROADCAST/
  MAIL_DRIVER removed). `DB_DATABASE` still points at `cristinarutz`.
- 3 production users (all admins); passwords unknown. For login tests,
  insert a temporary admin into `cristinarutz_prod` and delete it after.

### Gotchas

- `php artisan serve` doesn't pass `DB_DATABASE=...` through to the
  server. Run PHP's server from `public/` instead:
  `cd public && DB_DATABASE=cristinarutz_prod php -S 127.0.0.1:8765 ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php`
- CSRF checks are skipped under PHPUnit; check CSRF over HTTP.
- Mail with `MAIL_MAILER=log` is quoted-printable in the log; decode
  before extracting links. Real `.env` mail is SMTP — don't send for tests.
- zsh: `git show "$R:path"` breaks on `:a`, `:r` modifiers — use `"${R}:path"`.
- A git worktree sharing `vendor/` autoloads `App\` from the main tree;
  copy `vendor/` and `composer dump-autoload` there instead.
- `composer.phar` is still in the repo — unclear if the server needs it.

### Open items (not code)

- "Leistungen" text links to two project URLs that 404 (also live), see
  log below — content, fix in the admin.
- Production: confirm Imagick (`php -m | grep imagick`) and
  `upload_max_filesize`/`post_max_size` ≥ 30M; prepare the `.env` changes
  (Deploy notes).

## Log

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

2026-10-07: `02` step 4 done — own `AuthController` instead of
`laravel/ui`. See "Step 4" below.

2026-10-07: images done — signed URLs, AVIF/WebP, stored dimensions,
`images:warm` (`05`, "Done 2026-10-07: signed URLs…").

Found on the way (content, not changed): the "Leistungen" text (services
id 1) links to `/projekt/bebauung-buckerwies` and
`/projekt/wohnhaus-bergblumestrasse`, both 404 on the live site too (the
projects are `wohnbebauung-buckerwies` and `wohnhaus-bergblumenstrasse`).
Unpublished projects were reachable by URL — fixed 2026-10-07: 404 for
visitors, logged-in admins can still open them (as oxid `c195334`). Prod
copy: 4 unpublished → 404, 17 published → 200, none linked from the site.

Next: the frontend (`03`, `07`).

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

### Step 4: own login (2026-10-07)

- `App\Http\Controllers\AuthController`: login (5 failed attempts per
  e-mail + IP → one-minute lockout, as `ThrottlesLogins`), logout, forgot password
  (`throttle:6,1`), reset (min. 8 characters, confirmed; logs in and goes
  to `/administration` — laravel/ui went to `/`). Same URLs, route names
  and Blade views as before.
- Removed: `laravel/ui`, `Auth\*` controllers, the old `LoginController`,
  email verification (routes, view, `MustVerifyEmail` on `User`, the
  `verified` middleware on the admin route — all 3 users are verified) and
  password confirmation (unused).
- `phpunit.xml`: `CACHE_DRIVER` → `CACHE_STORE` (tests were using the file
  cache, so the login throttle leaked between tests).
- Tests: `AuthTest` (12) → 40 total. Over HTTP with prod data and a
  temporary admin user (removed): wrong password message, login → admin →
  logout, forgot password → mail (to the log) → link → reset → admin →
  login with the new password.
- Follow-up (2026-10-07): the reset mail is German
  (`resources/lang/de.json`: subject, lines, button, greeting, footer).
  Logout is POST only: the admin header submits a hidden form with the CSRF
  token; GET `/logout` is a 405. Clicked through in a browser: login → menu
  → Logout → home, admin redirects to login.

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

See `05-image-pipeline.md` ("Done 2026-10-07" sections).

## Admin

Not started (Vue 2). Changes so far on the Vue 2 admin: uploader shows the
server's error message; logout posts a form. Rebuilt with Mix.

## Public site JS

Not started.

## Tests

48 PHPUnit feature tests, in-memory SQLite (`php artisan test`):
`AuthTest`, `ImageRenditionTest`, `ImageStoreTest`, `ImageUploadTest`,
`MiddlewareTest`, `ProjectVisibilityTest`, `ResizeImagesTest`. No public
pages test yet (oxid has `PublicPagesTest`). Verification scripts against
production renders in `.rewrite/tools/`.

## Deploy notes

Order on the server (SSH + `git pull`, as oxid; built assets committed):

1. Snapshot the production DB and `storage/`.
2. Prepare `.env` (below), check Imagick and the PHP upload limits.
3. `git pull`, `composer install --no-dev --optimize-autoloader`
4. `php artisan migrate --force` (one new migration: image width/height)
5. `php artisan images:resize --dry-run`, then `php artisan images:resize`
6. `php artisan optimize:clear && php artisan optimize`
7. `php artisan images:warm` (a few minutes; AVIF is slow to encode)
8. Delete `storage/app/public/cache/` (image-cache's old renditions, 249 MB)
9. Check: login, admin, a project page, an image upload.

Details:

- **Production `.env` for Laravel 13** (config files now fall back to the
  framework's defaults, which changed): set `DB_CONNECTION=mysql`,
  `SESSION_DRIVER` (cookie, as locally — default is now `database`),
  `CACHE_STORE=file` (was `CACHE_DRIVER`; default is now `database`),
  `QUEUE_CONNECTION=sync`, `MAIL_MAILER=smtp`, `LOG_STACK=single,slack` and
  `LOG_LEVEL=debug` (what the old logging config hard-coded),
  `SANCTUM_STATEFUL_DOMAINS` with the production host(s), `APP_URL` the
  exact origin. Remove `CACHE_DRIVER`, `MAIL_DRIVER`, `BROADCAST_DRIVER`,
  `PUSHER_*`, `MIX_*`. (Local `.env` already done, 2026-10-07.)
- Migration `2026_10_07_120000_add_dimensions_to_images_table` (width/height,
  backfilled from the files — run it after `images:resize`, or run
  `images:resize` first; both keep the columns right). Then
  `php artisan images:warm` (~3–4 minutes on this machine; AVIF is slow
  to encode). Delete `storage/app/public/cache/` (image-cache).
- The image work (`images:resize`, stricter uploads) ships with the rework,
  not separately: `master` stays as it is until go-live (decided
  2026-10-07). On the server, after `composer install` and before
  `images:warm`: `php artisan images:resize --dry-run`, then
  `php artisan images:resize`. Check `upload_max_filesize`/`post_max_size`
  ≥ 30M and Imagick first (`05`).
