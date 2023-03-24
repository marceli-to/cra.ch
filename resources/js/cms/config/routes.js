import ErrorForbidden from '@/views/errors/Forbidden.vue';
import ErrorNotFound from '@/views/errors/NotFound.vue';
import Dashboard from '@/views/pages/Index.vue';

const routes = [

  // Home
  {
    name: 'home',
    path: '/administration/',
    component: Dashboard,
  },

  // Dashboard
  {
    name: 'dashboard',
    path: '/administration/',
    component: Dashboard,
  },

  // Authorization
  {
    name: 'forbidden',
    path: '/forbidden',
    component: ErrorForbidden,
  },
  {
    name: 'not-found',
    path: '/not-found',
    component: ErrorNotFound,
  }
];

export default routes;