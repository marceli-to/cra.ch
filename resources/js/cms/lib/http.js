import axios from 'axios';
import { notify } from '@/lib/notify';

// Same-origin SPA on Sanctum's session cookie: axios sends X-XSRF-TOKEN from
// the XSRF-TOKEN cookie by itself.
const http = axios.create({
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
});

/**
 * App-wide handling of failed requests. Callers still get the rejection
 * (forms read 422 errors via validationErrors) but need not notify.
 */
export function handleErrors() {
  http.interceptors.response.use(response => response, error => {
    const response = error.response;
    // The uploader passes { handleErrors: false } and shows the API's message
    if (error.config?.handleErrors === false) {
      return Promise.reject(error);
    }

    switch (response?.status) {
      // Session gone (expired, or logged out elsewhere); 419 when the CSRF
      // token expired with it. The login is a Blade page.
      case 401:
      case 419:
        window.location.href = '/login';
        break;
      case 403:
        notify({ type: 'error', text: '403 Zugriff verweigert' });
        break;
      case 404:
        notify({ type: 'error', text: '404 Nicht gefunden' });
        break;
      case 422:
        notify({ type: 'error', text: 'Bitte markierte Felder prüfen!' });
        window.scrollTo({ top: 0, behavior: 'smooth' });
        break;
      default:
        notify({ type: 'error', text: `${response?.status ?? 'Netzwerkfehler'} ${response?.data?.message ?? ''}`.trim() });
    }

    return Promise.reject(error);
  });
}

/**
 * Fields that failed validation, as { title: true }. The API returns
 * Laravel's default errors: { title: ['Titel wird benötigt'] }.
 */
export function validationErrors(error) {
  if (error.response?.status !== 422) {
    return null;
  }
  const fields = {};
  Object.keys(error.response.data.errors ?? {}).forEach(field => fields[field] = true);
  return fields;
}

export default http;
