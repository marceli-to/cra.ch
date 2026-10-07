# Admin: Vue 2 → Vue 3, Mix → Vite

> **Done 2026-10-07**, see `06-progress.md` → "Admin". Deviations: the
> admin keeps its own Sass and markup (oxid's JS building blocks only);
> login stays Blade; the API was not reshaped.

## Target shape (as in oxid/luvo today)

`<script setup>` throughout, composables instead of the 33 mixins,
`lib/http.js` (plain axios, `withCredentials`, CSRF, error → notification),
shared `components/ui/`, own notifications and uploader, one lightbox,
Phosphor icons. Copy the building blocks from oxid rather than rewriting.

## Dependency migration table

| Package | Current | Move to | Files | Effort |
|---|---|---|---|---|
| `vue` + `vue-template-compiler` + `vue-loader` | 2.7 | `vue ^3.5`, `@vitejs/plugin-vue` | — | |
| `laravel-mix` | ^6 | **Vite** + `laravel-vite-plugin` | `webpack.mix.js`, 3 blades | 0.25–0.5 day |
| **`vue2-dropzone`** | ^3.6 | **oxid's own `Uploader`** | 2 (`modules/{images,files}/components/Upload.vue`) + 2 configs | small |
| `vuedraggable` | ^2.24 | **SortableJS** (oxid `ac6a435`) | 6 | mechanical |
| `vue-advanced-cropper` | ^0.16 | `^2.8` | 1 (`modules/images/components/Edit.vue`) | easy |
| `vue-router` | ^3.6 | `^4` | `config/routes.js` + 9 route files | mechanical |
| `vue-notification` | ^1.3 | **oxid's own notifications** | `app.js`, 51 `$notify` in 26 files | mechanical |
| `vue-feather-icons` | ^5.1 | **`@phosphor-icons/vue`** | 34 files, 27 icons | mechanical |
| `vuex` | ^3.6 | **delete** — a `ref` in a composable | `config/store.js`, `App.vue` | free |
| `vue-axios` + `vue-axios-interceptors` | — | **delete** — `lib/http.js` | `app.js` | free |
| `moment` + `vue-moment` | — | **delete** — `Intl` / small helper | `mixins/DateTime.js` | small |
| `@tinymce/tinymce-vue` + self-hosted TinyMCE 7 | ^3 / 7.2 | **Tiptap 3** (oxid's editor component) | `config/tiny.js` + 7 Forms | decided |
| `cleave.js`, `vue-the-mask`, `vuejs-datepicker`, `nth-check`, `raw-loader`, `postcss-loader` | — | **delete** — unused | — | free |

## Vue 2 → 3 code changes

- `Vue.filter('truncate')` (`mixins/Filters.js`) → plain function import.
- 8 `.sync` in 7 Forms (likely the TinyMCE binding) → `v-model:prop`
  (or gone with the Tiptap component).

## TinyMCE → Tiptap

Toolbar today: undo/redo, bold, bullet list, link, superscript, remove
format, styles, code. Port oxid's Tiptap component (StarterKit + Link +
Superscript). Check what `styles` offers in `config/tiny.js` and map it.
Run oxid's `tiptap-roundtrip.mjs` over all stored HTML fields before
switching, so nothing is lost on first save. Delete
`public/assets/js/cms/tinymce` and `_tinymce` afterwards.
- `beforeDestroy` → `onBeforeUnmount` (`mixins/ErrorHandling.js`).
- 20 `$parent` hits, all in `modules/images` and `modules/files`
  (mixins `edit.js`, `crop.js`; `Upload`, `Actions`, `Edit`). Replace with
  props/emits while rewriting those modules — they're being replaced by
  oxid's image/file card anyway (oxid `8aa6ee4`).
- `$store.state.user` → `useUser()` composable.

## Order

1. Vite for the public site first (`07`); the admin moves to Vite and
   Vue 3 in one go (Vite + Vue 2.7 would be wasted work).
2. Foundation: `app.js`, router 4, `lib/http.js`, notifications, layout,
   `App.vue`, login redirect handling.
3. Simple pages (contact, about, service, resume, diary, home/article,
   project/category) — mostly list + form.
4. `modules/images` + `modules/files` (uploader, cropper, sort, edit).
5. `modules/galleries`, `links`, `videos`.
6. `project/project` + **`modules/grid`** (grid builder, 1,150 LOC):
   last, with feature tests on `GridController`/`GridItemController` first.

## Vite config

oxid's `vite.config.js` with inputs changed to
`resources/sass/web/app.scss`, `resources/js/web/app.js`,
`resources/sass/cms/app.scss`, `resources/js/cms/app.js` and the `@` alias
to `resources/js/cms`. Keep `publicDir: command === 'serve' ? 'public' : false`
and the Sass deprecation silencing.
