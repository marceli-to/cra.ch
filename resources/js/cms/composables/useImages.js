import { notify } from '@/lib/notify';
import http from '@/lib/http';
import { confirmDelete } from '@/lib/utils';

/**
 * Image actions of a form whose record has an images array (images: its
 * ref). An upload gets its image record right away; the form attaches it to
 * the record on save. Delete, publish, caption and crop go to the API right
 * away.
 */
export function useImages({ images, isLoading }) {
  async function request(call, success) {
    isLoading.value = true;
    try {
      const result = await call();
      if (success) {
        notify({ type: 'success', text: success });
      }
      return result;
    }
    catch {
      // Notified by the http error handler
    }
    finally {
      isLoading.value = false;
    }
  }

  // upload: the upload endpoint's response (name, original_name, …)
  async function store(upload) {
    const image = {
      ...upload,
      caption: null,
      coords_w: 0,
      coords_h: 0,
      coords_x: 0,
      coords_y: 0,
      preview: 0,
      publish: 1,
    };
    const response = await request(() => http.post('/api/image', image), 'Bild gespeichert!');
    if (response) {
      images.value.push({ ...image, id: response.data.imageId });
    }
  }

  async function destroy(image) {
    if (!confirmDelete()) {
      return;
    }
    if (await request(() => http.delete(`/api/image/${image.name}`))) {
      images.value.splice(images.value.indexOf(image), 1);
    }
  }

  async function toggle(image) {
    const response = await request(() => http.get(`/api/image/state/${image.id}`));
    if (response) {
      image.publish = response.data;
    }
  }

  function update(image) {
    return request(() => http.put(`/api/image/${image.id}`, { caption: image.caption }), 'Änderungen gespeichert!');
  }

  // The coords are already set on the image
  function saveCoords(image) {
    const { coords_w, coords_h, coords_x, coords_y } = image;
    return request(() => http.put(`/api/image/coords/${image.id}`, { coords_w, coords_h, coords_x, coords_y }), 'Änderungen gespeichert!');
  }

  return { store, destroy, toggle, update, saveCoords };
}

/**
 * Uploader props for images (config/images.php)
 */
export const imageUpload = {
  url: '/api/image/upload',
  restrictions: 'jpg, png | max. 30 MB, 60 Megapixel',
  acceptedFiles: '.png,.jpg,.jpeg',
  maxFiles: 99,
  maxFilesize: 30,
};
