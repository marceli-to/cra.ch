# Admin UI refresh (decided 2026-10-07, `04` #5)

As oxid `4dfc989`, `5f77c53`, `8c0b175`, `d787c01`, `adfd57a`, `2457ad4`:

- Phosphor (light) icons — **needed anyway**, `vue-feather-icons` is Vue 2
  only. 27 icons in 34 files; `GridGalleryIcon`/`GridIcon` are own SVG
  components and stay.
- 1px lines, calmer menu with sections, active page underlined, close button.
- Yes/no fields as toggles; one card for images and files.
- Regular weight for buttons/labels/tabs/tables, bold h2.
- Login: random published image as background.
- Land on the most-used list instead of an empty dashboard.

0.5–0.75 day on top of the port, because the components are rewritten anyway.
