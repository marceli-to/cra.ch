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

Next: `02` step 2 (Laravel 13).

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

- The image work (`images:resize`, stricter uploads) ships with the rework,
  not separately: `master` stays as it is until go-live (decided
  2026-10-07). On the server, after `composer install` and before
  `images:warm`: `php artisan images:resize --dry-run`, then
  `php artisan images:resize`. Check `upload_max_filesize`/`post_max_size`
  ≥ 30M and Imagick first (`05`).
