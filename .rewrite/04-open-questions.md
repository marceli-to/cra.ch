# Open questions

*All answered 2026-10-07 except the image driver in #2, which the
pipeline handles at runtime anyway — but see `05` on the huge originals.*

## 1. Production snapshot — DONE 2026-10-07

In `.rewrite/data/` (gitignored), loaded into `cristinarutz_prod`.
Numbers in `01-inventory.md`. Production is on **Hostpoint**.

Original note:

Local `storage/app/public/uploads` is empty. Need a production DB dump and
`storage/` copy before starting — for testing Glide against real images and
for a before/after image crawl (oxid recorded 6,187 URLs, all 200).

## 2. PHP version and image driver on production — ANSWERED 2026-10-07

**Production runs PHP 8.3 up to 8.5. Gate cleared.** Set the
`composer.json` floor to `^8.3`, not 8.5 — no dependency needs it.

Still open: Imagick or GD. Check on the server:

```
php -r 'echo PHP_VERSION, PHP_EOL;
        echo class_exists("Imagick") ? "imagick: yes" : "imagick: NO", PHP_EOL;
        if (class_exists("Imagick")) {
          echo "webp: ", Imagick::queryFormats("WEBP") ? "yes":"no", PHP_EOL;
          echo "avif: ", Imagick::queryFormats("AVIF") ? "yes":"no", PHP_EOL;
        }
        echo extension_loaded("gd") ? "gd: yes" : "gd: NO", PHP_EOL;'
```

The pipeline probes formats at runtime (luvo/oxid `ImageSupport`), so the
driver answer does not gate anything.

## 3. Login: keep `laravel/ui`? — ANSWERED 2026-10-07: replace (done, `06` step 4)

`laravel/ui` 4.6 supports Laravel 13, so keeping it works. oxid replaced it
with its own login. **Replace** `laravel/ui` with an own login, as in oxid — fewer moving parts, same
login page as oxid (random image background), and email verification
(`verify => true`) is probably not needed for a single admin user.
Check whether password reset by mail is used.

## 4. Editor: keep TinyMCE 7 or move to Tiptap 3? — ANSWERED 2026-10-07: Tiptap

TinyMCE 7.2 is supported, so not a security issue like oxid's 5. Keeping
it means `@tinymce/tinymce-vue ^6` and `license_key: 'gpl'`. Tiptap matches
oxid/luvo (one editor across projects) and drops ~megabytes of self-hosted
TinyMCE. The toolbar is small (bold, lists, link, superscript, styles,
code), so Tiptap covers it. **Tiptap 3**, with the HTML
roundtrip check oxid used (`.rewrite/tools/tiptap-roundtrip.mjs`).

## 5. Admin visual refresh? — ANSWERED 2026-10-07: yes

oxid got a light refresh (Phosphor, 1px lines, calmer menu, login image).
The icon swap is needed anyway (feather is Vue 2 only). **Do all of it** (`08`).

## 6. Deploy — ANSWERED 2026-10-07: as oxid

SSH + `git pull`, built assets committed, nothing built on the server.

## 7. wiretap — ANSWERED 2026-10-07: removed

`marceli-to/wiretap` only allowed Laravel ≤ 11. Removed from the app instead
of releasing a 13-compatible version. Remove `WIRETAP_*` from the production
`.env`.
