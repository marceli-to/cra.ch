# Estimate

| Block | Days | Notes |
|---|---|---|
| Backend: Laravel 13, slim skeleton, config trim, auth | 1–1.25 | Sanctum already in place; own login replaces `laravel/ui` |
| Images: image-cache → Glide, `<x-image>`, `images:warm` | 0.75–1 | Copy from oxid; only 1 crop URL shape to support |
| Tests: admin API + public pages + image renditions | 0.5 | oxid's `AdminTestCase` pattern |
| Vite (public + admin) | 0.25–0.5 | oxid's `vite.config.js` almost as-is |
| Admin Vue 3 + `<script setup>` | 2.5–3 | 74 files / ~7.6k LOC; grid builder ~1 day alone |
| Editor: TinyMCE 7 → Tiptap 3 | 0.25–0.5 | 8 files; decided |
| Admin UI refresh (Phosphor, 1px lines, menu) | 0.5–0.75 | decided; see `08` |
| Public JS: jQuery out, fancyBox | 0.75–1 | 8 small modules; see `07` |
| QA + deploy | 0.5 | |
| **Total** | **6–8** | |

## Compared to oxid

| | oxid | cristinarutz |
|---|---|---|
| Auth | JWT → Sanctum (~1 day) | already Sanctum |
| Search | Algolia → own search (~2 days) | none |
| Editor | TinyMCE 5 (EOL, XSS) → Tiptap | TinyMCE 7.2 → Tiptap (consistency) |
| vue2-dropzone files | 6 | 2 |
| vuedraggable files | 13 | 6 |
| `$parent` | 32 hits / 15 files | 20 hits / 8 files |
| Vue files | ~2× | 74 files, ~7.6k LOC |

## Out of scope

- Public site redesign.
- Sass `@import` → `@use` (silence the deprecations, as oxid did).
