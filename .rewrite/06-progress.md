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

Next: `02` step 1 (dead code).

## Backend

## Images

## Admin

## Public site JS

## Tests

## Deploy notes
