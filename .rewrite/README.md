# Rewrite notes — Laravel 11→13, Vue 2→3, Mix→Vite

Survey done **2026-10-07** against commit `e6c0bae` (branch `master`, clean tree).
Written so the next session can skip re-deriving all of this.

**Status (2026-10-07): backend, images, public site and admin done; review and deploy left.**
Start at `06-progress.md` → "Where things stand".

Modelled on the rework of **oxid.ch** (`.rewrite/` on branch
`rework/laravel-13-vue-3` in `github.com/jamon-marcel/oxid.ch`, done
2026-10-04), which itself followed **luvo.ch** (`github.com/marceli-to/luvo`).
Where a decision was already made and verified there, this set says so and
reuses it rather than re-litigating.

## Files here

| File | Contents |
|---|---|
| `00-estimate.md` | The headline numbers and what's in scope |
| `01-inventory.md` | What's actually in the codebase, measured |
| `02-backend-laravel13.md` | Dependency audit, skeleton, auth, step plan |
| `03-frontend-vue3.md` | Admin: package-by-package migration table, step plan |
| `04-open-questions.md` | What must be answered before (or while) starting |
| `05-image-pipeline.md` | image-cache → Glide |
| `06-progress.md` | Logbook: what is done, verified, and left to do |
| `07-frontend-js.md` | Public site JS: jQuery → vanilla ES modules, fancyBox |
| `08-admin-ui.md` | Admin look and feel, as in oxid |

## The 30-second version

- **6–8 working days.** Backend + Glide 2–2.5, Vite + Vue 3 admin 3–4,
  public JS ~1. oxid took 14–16; the delta is that cristinarutz is
  **already on Sanctum** and **has no search**, and the admin is about half
  oxid's size.
- **Laravel 11 is EOL.** `composer audit` reports ~30 advisories
  (laravel/framework, guzzle, symfony/http-foundation, commonmark, phpunit,
  psysh, flysystem). This is not a nice-to-have.
- Backend: every third-party dep has a Laravel 13 release.
  `marceli-to/wiretap` was removed on 2026-10-07 (it only allowed Laravel
  ≤ 11); `marceli-to/image-cache` goes away (Glide).
- Vue 2 code is clean: no `slot-scope`, `$listeners`, event bus, `$set`.
  The cost is replacing dead Vue 2 packages (dropzone, draggable, feather,
  notification, vuex, vue-axios), not fixing components.
- Biggest single file: `modules/grid/Index.vue` (1,150 lines, the grid
  builder). Port it last and test it hardest.

## Ground rules (taken from oxid)

- Admin ends up the way oxid/luvo are now: `<script setup>`, composables
  instead of mixins, `lib/http.js`, shared `components/ui/`. oxid did a
  straight Options-API port first and rewrote in a second pass; here, with a
  smaller admin, go straight to `<script setup>` per file.
- No design changes to the public site.
- Images: serve requested sizes + WebP/AVIF via Glide, not a 1:1 port.
- Login: own controller instead of `laravel/ui`.
- Editor: TinyMCE 7 → **Tiptap 3**.
- Admin gets oxid's visual refresh (`08`).
- PHP floor `^8.3` (production runs 8.3–8.5).
- Tests get added along the way (oxid ended with 118).
- Built assets are committed; nothing is built on the server.
