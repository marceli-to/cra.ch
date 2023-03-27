require('@/bootstrap');

// Vue
window.Vue = require('vue');

// Axios Interceptors
require('vue-axios-interceptors');

// Axios, Vue-Axios
import VueAxios from 'vue-axios';
import axios from 'axios';
window.axios = require('axios');
Vue.use(VueAxios, axios);

// Filters
require('@/mixins/Filters');

// Vue-Axios defaults
Vue.axios.defaults.withCredentials = true;

// Vue-Notifications
import Notifications from 'vue-notification';
Vue.use(Notifications);

// Vue-Router
import VueRouter from 'vue-router';
Vue.use(VueRouter);

// Loading indicator
import LoadingIndicator from "@/components/ui/LoadingIndicator";
Vue.component('LoadingIndicator', LoadingIndicator);

// Separator
import Separator from "@/components/ui/Separator";
Vue.component('Separator', Separator);


// Store
import store from '@/config/store';

// Routes
import baseRoutes from '@/config/routes';
import homeRoutes from '@/views/pages/home/config/routes';
import projectRoutes from '@/views/pages/project/config/routes';
import diaryRoutes from '@/views/pages/diary/config/routes';
import serviceRoutes from '@/views/pages/content/service/config/routes';
import contactRoutes from '@/views/pages/content/contact/config/routes';
import aboutRoutes from '@/views/pages/content/about/config/routes';
import resumeRoutes from '@/views/pages/content/resume/config/routes';
import imageRoutes from '@/modules/images/config/routes';

const router = new VueRouter(
  { 
    mode: 'history', 
    routes: [
      ...baseRoutes,
      ...homeRoutes,
      ...projectRoutes,
      ...serviceRoutes,
      ...contactRoutes,
      ...aboutRoutes,
      ...resumeRoutes,
      ...diaryRoutes,      
      ...imageRoutes,
    ]
  }
);

// App component
import AppComponent from '@/App.vue';

// Mount App
const app = new Vue({
  mixins: [],
  components: { 
    AppComponent
  },
  router,
  store
}).$mount('#app');