# Progress

## Where things stand (handover, 2026-10-07)

**Backend, images, public site and admin: done.** Left before go-live:
review in the browser by the user, the open items below, deploy.

- Branch `rework/laravel-13-vue-3`, pushed to `origin` (tracking,
  2026-10-07), on top of `e22f376` (= `origin/master` = production). `master` stays untouched until go-live (user's decision);
  nothing from the branch is to be cherry-picked there.
- Laravel 13.35 on the slim skeleton, PHP `^8.3` (platform 8.3.0),
  `composer audit` clean, Glide images with signed URLs + AVIF/WebP, own
  login (`AuthController`), 111 PHPUnit tests (`php artisan test`).
- Backend on **action classes** (`app/Actions/<Module>/…Action`,
  `execute()`), thin controllers, request classes for every write; see
  "Backend structure".
- **One Vite build for site and admin** (`npm run build` → `public/build`,
  committed; `npm run dev` for the dev server). Public JS is vanilla ES
  modules (13.8 KB), own lightbox. Admin is **Vue 3** (`<script setup>`),
  see "Admin". `npm audit` clean.

### Next

1. The user reviews the admin further (first review done 2026-10-07:
   buttons, menu, picker refined).
2. Open items (not code), below.
3. Deploy (see "Deploy notes").

Possible follow-ups, not needed for go-live (see "Admin" → "Not done"):
JSON resources and REST-ier routes for the API, Sass `@import` → `@use`.

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
  - `cristinarutz_qa` — scratch copy of `cristinarutz_prod` (2026-10-07,
    after the action-class work) with a QA admin; free to write to

- `storage/app/public/` holds the production files (resized as above);
  `storage/app/originals/` the untouched copies of the 34 resized files;
  `storage/app/.glide-cache/` warm.
- Local `.env` has `SANCTUM_STATEFUL_DOMAINS=cristinarutz.ch.test`: for
  the admin on the PHP server, start it with
  `SANCTUM_STATEFUL_DOMAINS=127.0.0.1:8765` (else every API call is 401).
- Local `.env` already uses the Laravel 13 names (`CACHE_STORE`,
  `LOG_STACK=single,slack`, `LOG_LEVEL=debug`; Pusher/MIX/BROADCAST/
  MAIL_DRIVER removed). `DB_DATABASE=cristinarutz_prod` (switched
  2026-10-07; before, it pointed at the old `cristinarutz`, so
  cristinarutz.ch.test referenced files no longer in `uploads`).
- A second dump from 2026-10-07 11:04 (`cristin4_cristina-07102026.sql`)
  differs from the 08:16 one only in the header timestamp: no re-import.
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

- **Change the live password of m@marceli.to**: it was in plain text in
  `database/seeds/UserSeeder.php` (now deleted, but still in the git
  history and on GitHub) and still matched production (checked
  2026-10-07 against the prod copy). The other seeded account's no longer
  matches.
- Three grid slots on live project pages show images that were deleted
  (grid_items 269, 298, 724 on projects 4, 3, 25), so they render empty:
  fill or empty them in the admin. Deleting an image now empties its
  slots; before, the cleanup never matched (see "Backend structure").

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
and the resized result. `.env` now uses `DB_DATABASE=cristinarutz_prod`;
override it per command for the scratch DB (`cristinarutz_qa`).

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

2026-10-07: public site built with Vite (see "Public site JS").

2026-10-07: public JS without jQuery, own lightbox (see "Public site JS").

2026-10-07: action classes for all controllers, request classes, models
slimmed, dead code out; bugs found by the new tests fixed (see "Backend
structure").

2026-10-07: local `.env` switched to `cristinarutz_prod` (a homepage tile
was broken because the old DB referenced replaced files). Fresh 11:04 dump
identical to the 08:16 one. `npm run build` and `npm run admin:build`
rerun: output unchanged from the committed build.

2026-10-07: admin on Vue 3 + Vite, with the UI refresh (see "Admin").
Then UI refinements after the user's review (buttons, menu, picker,
toggle; see "Admin" → "UI refinements"). All pushed (`ae69ab8`).
Validation errors in Laravel's default format; upload CSRF exemption gone;
login background. 111 tests.

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

### Backend structure (2026-10-07)

Pattern as in rework.strut.ch (`../rework.strut.ch`, newest of the
user's projects with it): `app/Actions/<Module>/<Verb>Action` with
`execute()`, called from thin controllers; site pages get their data from
`Actions/Site/Get…`.

- **Actions**: `Content/{Save,Delete,Reorder,ToggleFlag,ToggleAttribute}`
  (shared by about, contact, service, diary, article, resume, team,
  image), `Image/{Upload,Store,Attach,Crop,Delete,Duplicate,Render}`,
  `Project/{Save,Copy,MakeSlug}`, `Category/Save`,
  `Grid/{StoreRow,DeleteRow,SetItem,ResetItem}`, `Auth/{Login,
  ResetPassword}`, `Site/{GetHome,GetTeam,GetDiary,GetContact,
  GetService,GetProject,GetWorklist,GetNavigation}`. Reads that are one
  query stay in the controller. The menu's data comes from a view composer
  (`AppServiceProvider`) instead of `BaseController`'s constructor (which
  also ran on the login page).
- **Requests**: `AdminRequest` (base) answers 422 with
  `errors: {field: [{field, error}]}`, what the admin's ErrorHandling
  reads; plain string messages. One request per form with rules for
  every field (`validated()` is what gets saved); new: `ImageStore`,
  `ImageUpdate` (caption, description only), `ImageCrop`, `GridRow`
  (owner from a whitelist instead of `"App\Models\" . input`),
  `GridItem`. Reorder endpoints validate `{key: [{id, order}]}`
  (`Controller::orderItems`).
- **Models**: traits `HasPublishFlag` (`publish` attribute; `hasFlag()`
  reads eager-loaded flags, `flags` hidden from JSON), `HasImages`,
  `HasGrids`; `GridItem::captionFor()` replaces `AppHelper::caption()`;
  `GridItem::loadLinkedProjects()`. Return types, no empty docblocks.
- **Removed**: `AppHelper`, `DateHelper` and their aliases (only
  `caption()` was used), empty `app/Facades`, `Base` model, `CategoryProject`
  pivot, the `File` model + `FileController` + `/api/file*` routes (the
  files module is imported in the admin but never rendered; `files` is
  empty on production; its upload took any file type into public storage)
  and its CSRF exemption, `UploadController` (no route), unrouted
  `delete()` methods, `Services/Media` (store → `Image/UploadAction`,
  duplicate → `Image/DuplicateAction`; the rest unused), unused relations
  and scopes, `BaseController`, `database/seeds` (could not load: composer
  pointed at `database/seeders`; plaintext passwords) and the Laravel 7
  `UserFactory`, `fakerphp/faker`, the dead `/api/user` closure (shadowed
  by the controller route).
- **API needs `role:admin`** now, not only a login. `CheckRole` 403s
  instead of failing on a missing user.

Bugs found by the new tests (fixed):

- **Every validation error of the admin forms was a 500** on this branch:
  the request classes returned arrays as messages, which Laravel 13 no
  longer formats. And even a 422 never reached the user: the admin
  registered its interceptors on `require('axios')` while the components
  use `import axios` — two instances with axios 1.x, so 401/403/404/422/500
  handling (notice, field marking, redirect to login) never ran (likely on
  production too, same axios). One instance now (`cms/bootstrap.js`);
  checked in the browser: empty title → notice + field marked.
- Deleting an image looked up grid slots by `image_id = <file name>`, so
  slots kept pointing at the deleted image (3 on production, see Open
  items). Now the slots are emptied (kept, so the admin can fill them).
- A project whose slug has a suffix (`wohnhaus-im-zwei-29`) got a new
  slug, i.e. URL, on every save; now only when the title changes.
- `ResumeStoreRequest` sent the description error to the `periode` field;
  `ServiceStoreRequest` defined one message key twice.
- Copied images kept the original's uuid.

Changed on purpose (otherwise identical): the project page's browse
links for a project not in the list (unpublished preview, or published
without detail page: 3 on production) — previous is the last, next now
the first (was the second); `GetProject` uses modulo now.

Verified:

- `.rewrite/tools/snapshot.php`: status and body of every public page,
  21 projects as guest and admin, 5 legacy image redirects and every admin
  GET with every id (888 requests) on the prod copy, before and after:
  **identical**, except the browse link above (10 pages). Queries:
  **4,538 → 2,351** (work list 66 → 7, project page 28 → 9, team 23 → 7,
  admin project form up to 99 → 14).
- New tests (in-memory SQLite): `Api/ContentApiTest` (8 modules: store,
  update, toggle, delete, validation format), `Api/ProjectApiTest`,
  `Api/ImageApiTest`, `Api/GridApiTest`, `PublicPagesTest`; written
  against the old code first. 111 tests.
- Admin in Chromium on a scratch DB copy (`cristinarutz_qa`, with a QA
  admin user; can be dropped): login, project save, validation display,
  toggle, image caption + crop, grid slot reset/set, reorder, upload →
  record → delete by name.

Not done now (with the admin port, `03`):

- **JSON resources / response shapes.** The Vue 2 admin reads the models'
  array form (incl. appended attributes like `abstract`, `preview` that it
  never uses, and `categories` on every project). Kept identical; define
  resources when the admin is rewritten.
- **Routes**: toggles and project copy are GETs that write; URLs like
  `image/state/{id}`, `resumes/{teamMember}` vs `resume/{id}`. Change
  with the admin's API module.
- Image upload: CSRF exemption stays until the uploader sends the token.
- Admin: `.then()` without `.catch()` everywhere (unhandled rejections in
  the console on every error); dead modules `files`, `links`, `videos`,
  `galleries`; the diary list posts `/api/diary/order`, which never
  existed.
- The empty `files` table stays (a drop migration wasn't worth it now).
- Mix admin bundle rebuilt for the axios fix (it also picks up the
  dependency versions moved by the Vite install).

## Images

See `05-image-pipeline.md` ("Done 2026-10-07" sections).

## Admin

### Vue 3 + Vite (2026-10-07)

Ported in one go (Vue 2.7 + Mix → Vue 3.5 + Vite 8, `resources/js/cms`),
building blocks from oxid, look kept from cristinarutz's own admin Sass
(`resources/sass/cms`, its markup classes) with the refresh of `08`.

- **Structure**: `app.js`, `App.vue`, `router.js` (vue-router 4, lazy
  routes, forms get `type` as a prop), `lib/` (`http.js`: one axios
  instance, XSRF cookie, error → notification, 401/419 → `/login`;
  `notify.js`, `images.js`, `utils.js`), `composables/` (`useListing`,
  `useResourceForm`, `useOrder`, `useImages`), `components/ui/`
  (oxid's `Lightbox`, `Uploader`, `Tabs`, `Toggle`, `SortableList`,
  `Notifications`, Tiptap `editor/`; own `ContentHeader`, `ContentFooter`,
  `ListActions`, `AddButton`), `components/images/ImageManager.vue`,
  `components/grid/` (grid builder), `views/<module>/{Index,Form}.vue`.
  33 mixins, Vuex, vue-axios(-interceptors), vue-notification,
  vue-feather-icons, vue2-dropzone, vuedraggable, TinyMCE (self-hosted) and
  all their files are gone.
- **Grid builder**: the 1,150-line template (one block per layout) is a
  table now (`components/grid/layouts.js`: area + aspect ratio + "can hold
  an article" per slot, layouts per owner); `Grid.vue` + `GridItem.vue`,
  pickers in a `Lightbox` (`ImagePicker`, `ArticlePicker`), the layout
  sketches (`LayoutIcon.vue`) unchanged.
- **Editor**: Tiptap 3 with TinyMCE's toolbar (undo/redo, Überschrift 1/2,
  bold, bullet list, superscript, "Trennung verhindern" =
  `span.no-word-break`, link dialog, remove formatting). Dropped: the
  source view and "Unterstrichen" (`span.underline-static`, no CSS on the
  site, unused in content). `.rewrite/tools/tiptap-roundtrip.mjs` over all
  84 stored values (`.rewrite/data/richtext.json`): 3 differ, all
  cosmetic (bold/no-word-break spans nested the other way round, a pasted
  `font-family: Gotham` span dropped); text, links, breaks unchanged.
  A form saved without touching an editor sends its HTML unchanged.
- **Images**: oxid's uploader (one by one, progress, the API's reason on
  rejection) **sends the CSRF token**; the `validateCsrfTokens(except:)`
  for `api/image/upload` is gone. Edit (caption) and cropper (formats per
  form, "Frei") in a `Lightbox`; vue-advanced-cropper 2. Grid and preview
  images use `/img/crop/{name}/1500?c={coords}` — the coords in the query
  so the cached 301 changes with a re-crop.
- **API**: unchanged URLs and response shapes (forms unwrap `{contact: …}`
  via `key`). Validation errors are **Laravel's default** now
  (`errors: {field: [message]}`), for the admin requests and the upload
  (the Dropzone `error` key is gone); tests adjusted.
- **Dead code not ported**: modules `files`, `links`, `videos`,
  `galleries`; the dashboard and the two overview pages (Startseite,
  Projekte & Kategorien); `Chip`, `Pill`, `Separator`, `RadioButton`;
  the diary list's call to the non-existent `/api/diarys/order`; the
  unused "Vorschaubild" star (`hasPreviewState` was never set). Dead Sass
  partials (cards, chip, pills, collapsible, widgets, pagination, overlays,
  dropzone, datepicker, sidebar, `btn-danger`) and the fonts' `.eot`/`.svg`.
- **Refresh (`08`)**: Phosphor icons (light); 1px lines
  (`$border-width`); menu with sections (Startseite: Layout/Artikel,
  Projekte: Projekte/Kategorien, Tagebuch, Leistungen, Über uns:
  Text/Team, Kontakt) — Team was missing from the old menu — with the
  current page underlined and a close button; yes/no as toggles; list
  rows labelled with their text instead of "Kontakt"/"Leistungen";
  `/administration` lands on the project list; login with a random
  landscape image of a published project as background
  (`AuthController::splash()`). Login stays a Blade page.
  Not applied from `08`: regular weight for buttons/labels/tabs (the
  admin's own type was kept) and oxid's one card for images and files
  (there are no files here; images keep the upload tiles).
- Verified in Chromium on `cristinarutz_qa`: every page renders without
  console errors (19 routes); project edit/save (new image attached, texts
  unchanged in the DB), validation (field + tab + notice), upload with
  CSRF, crop saved, list toggle, resume create (back to the member's
  list), grid: image picker (project; home: projects, diary, pages),
  article picker, reset slot, add/delete row, sort view; logout; login
  background. Not tested by hand: drag ordering (SortableList as in oxid),
  Firefox/Safari.

### UI refinements (2026-10-07, after the user's review)

- Buttons: two sizes (38px page actions, 30px inline), one solid button
  per page (Speichern); add/sort/fill as outline buttons; "Zeile löschen"
  a muted text button; a slot's delete is a trash icon shown on hover;
  empty slots dashed. Old button partials (global, tertiary, colour
  variants) removed.
- Menu: every group labelled (Startseite, Projekte, Seiten), one link
  style, current page underlined, panel 320px, above the fixed footer.
- Home image picker: sources (published projects, then Tagebuch,
  Leistungen, Team, Kontakt) on the left, the selected one's images on
  the right under its name; the first project is shown at once. Images
  whole on a light square via the new `/img/small/{file}` rendition
  (within 400 × 400, `ImageController::small`, tested).
- Toggle black when on.

Not done (not needed for go-live):

- JSON resources / REST-ier routes (toggles and project copy are GETs that
  write; `image/state/{id}`, `resumes/{teamMember}` vs `resume/{id}`).
- Sass still on `@import` (deprecation warnings silenced).
- Grid rows added get `order = -1`, so they appear first (as before).

## Public site JS

### Vite (2026-10-07)

- `vite.config.js` as oxid's (Vite 8.3, laravel-vite-plugin 3.2), entries
  `resources/sass/web/app.scss` and `resources/js/web/app.js`; the
  layouts use `@vite`. Mix builds only the admin now (`admin:dev`,
  `admin:watch`, `admin:build`). Old `public/assets/{css,js}/app.*`
  deleted.
- `require` → `import`; `bootstrap.js` sets the jQuery global for
  fancyBox 3. Vendored libraries unchanged.
- Sass URLs are absolute (`/assets/img/…`, `/assets/css/fonts/…`) since
  the CSS now sits in `public/build/assets`.
- **Found by the comparison:** Vite's CSS step (lightningcss) rewrites the
  legacy `:-moz-placeholder` / `:-ms-input-placeholder` (Firefox ≤ 18, IE)
  to `:placeholder-shown`, so their `padding: 0; line-height: 1` hit the
  empty login inputs. Deleted those rules (and the `::placeholder.is-invalid`
  ones, invalid CSS that browsers drop) plus the `*zoom: 1` IE hack, so the
  build needs no `errorRecovery` and fails on broken CSS.
- Verified with Playwright (`.rewrite/tools/qa/`, Chromium) against the
  Mix build on prod data: computed styles of every element identical on
  9 pages (only `0%` vs `0px` in a `background-position`); screenshots of
  13 pages at 1440/390 px identical apart from lazy-load timing; lazy
  loading, menu, mehr/weniger, project info, lightbox (open, next,
  caption, Esc) and imprint behave the same; no console errors.
- Bundles: JS 170 KB (gzip 57), CSS 229 KB (gzip 34), as before.

### Without jQuery (2026-10-07)

- Modules are ES modules with `export function init()`; `app.js` imports
  and calls them (as oxid). jQuery, `bootstrap.js` and the jQuery global
  are gone; `truncate.js` is vanilla, same DOM steps as before.
- **Own lightbox** (`modules/lightbox.js`, `components/_lightbox.scss`)
  instead of fancyBox 3 — no GPL/commercial licence question, no CDN
  script. Same look: white overlay, the site's cross/chevron icons at the
  same positions, caption bar with the caption and "N/M", arrows greyed
  out at the ends (no loop), fade in (366 ms) and crossfade (600 ms),
  images never upscaled, side margins 60/90/120 px. Same behaviour: one
  group per page in document order, Esc/arrow keys, swipe, a click beside
  the image closes. Links: `data-lightbox` (was `data-fancybox="gallery"`).
  Differences: images stop above the caption bar (fancyBox let tall
  images run under it, up to 50 px hidden) and the arrows are centred on
  the image area; the page is scroll-locked while open; buttons are
  `<button>`s with German labels, focus stays in the dialog and returns
  to the thumbnail; no mouse-wheel navigation (fancyBox's default) and no
  drag-follow while swiping.
- Removed: vendored fancyBox 3 (JS + Sass, and the unused v5 Sass), the
  unused `@fancyapps/ui` 5 from jsDelivr in the footer, `vhcheck` (its
  `--vh-offset` was used nowhere), `touch.js` (queried an undefined
  selector, so it never did anything; no `data-touch` in the markup),
  `modernizr.js` (none of its classes are used). Vendored lazyload →
  `vanilla-lazyload` 19 from npm.
- Bundles: JS 170 → **13.8 KB** (gzip 57 → 4.9), CSS 229 → 217 KB.
- Verified (`.rewrite/tools/qa/`): computed styles identical to the
  previous build on 9 pages; screenshots of 13 pages at 1440/390 px
  pixel-identical; lazy loading loads the same images; lightbox measured
  against fancyBox 3 at 1440 and 390 px (same caption and button
  positions, image sizes as above); all interactions in Chromium and
  WebKit, no console errors. Not tested: Firefox, real devices.
- `npm audit` listed advisories in the Mix/webpack toolchain of the
  Vue 2 admin; gone with the admin port (now clean).

## Tests

111 PHPUnit feature tests, in-memory SQLite (`php artisan test`):
`AuthTest`, `ImageRenditionTest`, `ImageStoreTest`, `ImageUploadTest`,
`MiddlewareTest`, `ProjectVisibilityTest`, `PublicPagesTest`,
`ResizeImagesTest`, `Api/*` (content modules, projects, images, grids).
Verification scripts in `.rewrite/tools/`: `snapshot.php` (every page and
admin GET on prod data, for before/after diffs), `qa/` (Playwright).

## Deploy notes

Order on the server (SSH + `git pull`, as oxid; built assets committed —
`public/build` for site and admin; the old `public/assets/js/cms` and
`public/assets/css/cms/app.css` are deleted by the pull):

1. Snapshot the production DB and `storage/`.
2. Prepare `.env` (below), check Imagick and the PHP upload limits.
3. `git pull`, `composer install --no-dev --optimize-autoloader`
4. `php artisan migrate --force` (one new migration: image width/height)
5. `php artisan images:resize --dry-run`, then `php artisan images:resize`
6. `php artisan optimize:clear && php artisan optimize`
7. `php artisan images:warm` (a few minutes; AVIF is slow to encode)
8. Delete `storage/app/public/cache/` (image-cache's old renditions, 249 MB)
9. Check: login, admin, a project page, an image upload, a crop.

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
