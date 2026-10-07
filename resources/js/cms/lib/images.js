/**
 * Url of an uploaded image for the admin:
 *
 * 'thumbnail'  300 × 300, cropped to fill
 * 'original'   the upload (the cropper's source)
 * 'crop'       the stored crop (redirects to the signed URL). The redirect
 *              is cached, so the coords go into the URL: a re-crop gets a
 *              new one.
 */
export function imageUrl(image, template = 'crop') {
  if (template !== 'crop') {
    return `/img/${template}/${image.name}`;
  }
  const coords = [image.coords_w, image.coords_h, image.coords_x, image.coords_y].map(value => Math.floor(value || 0));
  return `/img/crop/${image.name}/1500?c=${coords.join(',')}`;
}

/**
 * Resolves once the browser has loaded the image at url.
 */
export function preloadImage(url) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.onload = () => resolve(url);
    img.onerror = reject;
    img.src = url;
  });
}
