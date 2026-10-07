import { ref } from 'vue';
import { notify } from '@/lib/notify';
import http from '@/lib/http';
import { confirmDelete } from '@/lib/utils';

/**
 * A list of records with publish toggle and delete.
 *
 * list      url of the list, e.g. '/api/articles'
 * resource  resource path below /api, e.g. 'article'
 *           (toggle: GET /api/{resource}/state/{id},
 *            delete: DELETE /api/{resource}/{id})
 */
export function useListing({ list, resource, isLoading = ref(false) }) {
  const items = ref([]);
  const isFetched = ref(false);

  async function fetch() {
    isLoading.value = true;
    try {
      const { data } = await http.get(list);
      items.value = data.data;
      isFetched.value = true;
    }
    catch {
      // Notified by the http error handler
    }
    finally {
      isLoading.value = false;
    }
  }

  async function toggle(id) {
    isLoading.value = true;
    try {
      const { data } = await http.get(`/api/${resource}/state/${id}`);
      items.value.find(item => item.id === id).publish = data;
      notify({ type: 'success', text: 'Status geändert' });
    }
    catch {
      // Notified by the http error handler
    }
    finally {
      isLoading.value = false;
    }
  }

  async function destroy(id) {
    if (!confirmDelete()) {
      return;
    }
    isLoading.value = true;
    try {
      await http.delete(`/api/${resource}/${id}`);
      notify({ type: 'success', text: 'Eintrag gelöscht' });
    }
    catch {
      // Notified by the http error handler
    }
    await fetch();
  }

  fetch();

  return { items, isFetched, isLoading, fetch, toggle, destroy };
}
