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

## Done 2026-10-07: cap originals, stricter uploads

On the current Laravel 11 code, so it can go live before the rework.

- `config/images.php`: `max_edge` 6000 (env `IMAGES_MAX_EDGE`), uploads
  jpg/jpeg/png, max 30 MB, max 60 MP.
- `App\Services\ImageResizer`: dimensions from the header (EXIF rotation
  applied, no decoding), in-place scale-down via a temp file. Imagick when
  loaded (keeps ICC/EXIF, resets orientation, outside PHP's memory_limit),
  GD otherwise.
- **`php artisan images:resize [--max=] [--file=*] [--dry-run]`**: scales
  originals above `max_edge`, copies the untouched file to
  `storage/app/originals/` once (a second run never overwrites it), scales
  `coords_*` of every record with that name (incl. soft-deleted) and
  updates `ratio`/`size`, deletes the file's image-cache renditions.
  Safe to rerun; run it again right before go-live.
- Uploads: `ImageUploadRequest` (bail; extension **and** content must be
  jpg/png; size; megapixels read from the header before anything decodes
  it), German messages, 422 with `error` for Dropzone. `Media::store`
  scales down on upload, then records ratio/orientation of the stored file.
- Admin uploader: shows the server's message; previously a rejected upload
  was passed on to `store()` as if it were an image.
- Tests: `ImageUploadTest` (7), `ResizeImagesTest` (3).

On the production snapshot: 34 of 448 originals over 6000 px, 233 MB →
see `06-progress.md`. Crop of the 15894 px section before/after: identical
(RMSE 0.014). Fully grey images are written as greyscale JPEGs by
ImageMagick (saturation 0, profiles kept) — harmless.

Production before running it: `php -m | grep imagick` (else GD with
`--memory`), and PHP `upload_max_filesize`/`post_max_size` ≥ 30M for the
new upload limit.

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
