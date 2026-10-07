# Backend: Laravel 11 → 13

## Why now

`composer audit` on 2026-10-07: ~30 advisories, several high
(laravel/framework, guzzle, symfony/http-foundation, league/commonmark,
phpunit). Laravel 11 no longer gets security fixes.

## Dependency plan

| Package | Target | Action |
|---|---|---|
| `laravel/framework` | `^13.0` | |
| `php` | `^8.3` | Laravel 13 floor; prod runs 8.3–8.5 |
| `laravel/sanctum` | `^4.3` | no change in usage |
| `laravel/tinker` | `^3.0` | |
| `laravel/ui` | — | **remove**, own login (`04` #3) |
| `intervention/image-laravel` | `^4` | check `Services/Media.php` against the v4 API |
| `league/glide` | `^4` | new, replaces image-cache (`05`) |
| `marceli-to/image-cache` | — | remove |
| `marceli-to/wiretap` | — | **removed 2026-10-07** (package, config, `Handler::report` hook) |
| `spatie/laravel-model-flags` | `^1.5` | |
| `spatie/laravel-sluggable` | `^4.0` | check `SlugOptions` usage in `Category` |
| `opcodesio/log-viewer` | `^3.24` | republish config/assets if needed |
| `nesbot/carbon` | drop explicit require (framework brings 3) | check date formatting in views/resources |
| `doctrine/dbal` | — | remove |
| `unsplash/unsplash` | — | remove (unused) |
| `guzzlehttp/guzzle` | `^7.10` | |
| dev: `phpunit` | `^12`, `collision ^8`/latest, `ignition` latest | |

## Steps (oxid order)

1. **Delete dead code first** (own commit): `unsplash`, `config/imagecache.php`,
   `BroadcastServiceProvider` + `routes/channels.php` + `config/broadcasting.php`,
   `server.php`, `public/assets/js/cms/_tinymce` vs `tinymce` (keep the one
   in use), unused `Auth/*` controllers (`Register`, `Confirm`?).
2. **Upgrade to 13** in place: composer.json bump, `composer update`,
   fix what breaks.
3. **Slim skeleton** (oxid `5c014f1`, `5bdc257`): `bootstrap/app.php` with
   `withRouting` / `withMiddleware` / `withExceptions`; delete both kernels,
   `Handler.php`,
   `RouteServiceProvider`, `Auth`/`Event` providers; register `role`
   middleware alias; trim config to the Laravel 13 defaults and keep only
   files with real changes (`pages`, `seo`, `client`, `sanctum`,
   `filesystems`, …). `php artisan route:cache` must work afterwards.
4. **Auth** (`04` #3): remove `laravel/ui`, `Auth::routes()` and
   `app/Http/Controllers/Auth/*`; own `LoginController` (login, logout,
   password reset if used) and login view as oxid, random published image as
   background. Drop `verified` from the admin route unless verification is
   really used. Keep `/login` and `/logout` URLs.
5. **Images → Glide** (`05`).
6. **Tests**: copy `tests/Feature/Admin/AdminTestCase.php` (in-memory
   SQLite), one test per API resource, `PublicPagesTest`,
   `ImageRenditionTest`. Migrations must run on SQLite.

## `.env` changes for deploy (from oxid's deploy notes)

- `DB_CONNECTION=mysql` must be set (framework default is `sqlite`).
- `QUEUE_CONNECTION=sync` (default is `database`).
- `CACHE_DRIVER` → `CACHE_STORE`, `FILESYSTEM_DRIVER` → `FILESYSTEM_DISK`.
- `APP_URL` = exact production origin; `SANCTUM_STATEFUL_DOMAINS` checked.
- Remove `WIRETAP_*`, `BROADCAST_DRIVER`, `PUSHER_*`, `MIX_*`, unused `REDIS_*`.
