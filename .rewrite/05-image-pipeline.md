# Images: image-cache → Glide

Same move as oxid (`a809fd2`, `62c73c4`, `ffac092`, `92e0eed`) and luvo.
Simpler here: only one public URL shape.

## Today

`marceli-to/image-cache` registers `/img/{template}/{file}` and
`/img/crop/{file}/{maxSize?}/{coords?}/{ratio?}`. Used:

- Public: `/img/crop/{name}/{maxSize}/{coords}` from
  `components/image.blade.php` (per breakpoint), gallery links (2000),
  project OG image (1500).
- Admin: `/img/thumbnail/{name}`, `/img/original/{name}`, `/img/crop/...`
  (`modules/images/mixins/utils.js`).
- `ImageController::destroy` calls `ImageCache::clearImageCache()`.
- Coords come from the admin cropper and are stored on `Image.coords`.

## Production numbers (2026-10-07)

384 live images, all files present; 64 orphan uploads; 1,382 cached
renditions. **The originals are very large**: 29 files over 40 MP
(28 live), the biggest 8896×14192 = 126 MP and a 15894×5585 section.
Plans and sections (`GRT_*`, `CGB_07_*`, `Regenbecken_*`) are the culprits.

GD decodes to ~4–5 bytes/pixel: a 126 MP image needs ~600 MB, far above
the 128M local `memory_limit` and above what Hostpoint will allow for FPM.
image-cache already renders these today, so either production has Imagick
(likely — Hostpoint has it; luvo uses Imagick with AVIF there) or the
renditions came from a bigger limit. **Before relying on Glide:**

1. Run the driver check in `04` #2 on the server.
2. Cap originals on upload (e.g. longest side 6000 px, done once with
   Imagick/Intervention in `Services/Media.php`), and run a one-off command
   to downscale the 125 existing originals wider than 4000 px — keeping the
   untouched file aside. **Coords are absolute pixels of the original**
   (`coords_w/h/x/y`; 38 live images have a crop, e.g. 7897×4936+3858+215
   on the 15894 px section), so the same command must scale the coords by
   the same factor, and the cropper must keep working on the stored file.
3. Glide: set `max_image_size` so a request can never decode an
   unbounded image, and warm the renditions with `images:warm` from the CLI
   (higher memory limit) rather than on first page view.

## Target

- Port oxid's image controller / `IsImage` / `<x-image>`:
  signed `/img/{file}?w=…&crop=…&fm=…`, Glide on Imagick or GD,
  `ImageSupport::modernFormats()` → `<source type="image/avif|webp">`.
- `components/image.blade.php` keeps its breakpoint map (`$maxSizes`) but
  emits real widths + modern formats. Lazy loading: keep the current
  `data-srcset` behaviour or switch to native `loading="lazy"` (`07`).
- Store image width/height on upload (oxid migration
  `add_dimensions_to_image_tables`) → no layout shift, correct `width`/`height`.
- Keep the old `/img/crop/...` URL working as a redirect for a while
  (OG images are cached by social networks and search engines).
- `images:warm` command; delete `storage/app/public/cache/` after go-live.
- Never serve or keep a broken rendition (oxid `92e0eed`).

## Verify

Before/after crawl of every image URL on the production snapshot
(oxid `.rewrite/tools/image-crawl.py`, `image-compare.sh`).
