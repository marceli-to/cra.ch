import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { notify } from '@/lib/notify';
import http, { validationErrors } from '@/lib/http';

/**
 * Create/edit form for an API resource.
 *
 * type      'create' | 'edit' (route prop)
 * endpoint  resource path below /api, e.g. 'article'
 *           (GET {id}, POST to create, PUT {id} to update)
 * key       the record's key in the GET response ({ article: {...} });
 *           null if the response is the record
 * model     () => empty record; in edit mode, null values from the API
 *           fall back to these defaults
 * redirect  route to go to after saving, or (record) => route
 * titles    { create, edit }
 * load      extra requests (() => Promise) the form waits for
 * loaded    (data) => void, with the whole GET response
 */
export function useResourceForm({ type, endpoint, key = null, model, redirect, titles, load = [], loaded = () => {} }) {
  const route = useRoute();
  const router = useRouter();

  const isEdit = type === 'edit';

  const record = ref(model());
  const errors = ref({});
  const isLoading = ref(false);
  const isFetched = ref(false);
  const title = computed(() => isEdit ? titles.edit : titles.create);

  async function fetch() {
    const { data } = await http.get(`/api/${endpoint}/${route.params.id}`);
    const found = key ? data[key] : data;
    const defaults = model();
    for (const field in defaults) {
      found[field] ??= defaults[field];
    }
    loaded(data);
    record.value = found;
  }

  async function init() {
    isLoading.value = true;
    try {
      await Promise.all([isEdit ? fetch() : null, ...load.map(request => request())]);
      isFetched.value = true;
    }
    catch {
      // Notified by the http error handler
    }
    finally {
      isLoading.value = false;
    }
  }

  async function submit() {
    isLoading.value = true;
    try {
      if (isEdit) {
        await http.put(`/api/${endpoint}/${route.params.id}`, record.value);
      }
      else {
        await http.post(`/api/${endpoint}`, record.value);
      }
      errors.value = {};
      router.push(typeof redirect === 'function' ? redirect(record.value) : redirect);
      notify({ type: 'success', text: isEdit ? 'Änderungen gespeichert!' : 'Daten erfasst!' });
    }
    catch (error) {
      errors.value = validationErrors(error) ?? errors.value;
    }
    finally {
      isLoading.value = false;
    }
  }

  // A field's error goes away once it gets focus
  function clearError(field) {
    delete errors.value[field];
  }

  init();

  return { record, errors, isEdit, isLoading, isFetched, title, submit, clearError };
}
