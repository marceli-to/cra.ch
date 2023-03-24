require('@member/bootstrap');

// Vue
import Vue from 'vue';
window.Vue = Vue;

// Axios Interceptors
require('vue-axios-interceptors');

// Axios, Vue-Axios
import VueAxios from 'vue-axios';
import axios from 'axios';
window.axios = require('axios');
Vue.use(VueAxios, axios);

// Vue-Axios defaults
Vue.axios.defaults.withCredentials = false;

// Loading indicator
import LoadingIndicator from "@member/components/ui/LoadingIndicator";
Vue.component('LoadingIndicator', LoadingIndicator);

// Vue-Notifications
import Notifications from 'vue-notification';
Vue.use(Notifications);

// App component
Vue.component('member-form', require('@member/views/Form.vue').default);

// Mount App
if (document.getElementById("app-member")) {
  const app = new Vue({}).$mount('#app-member');
}