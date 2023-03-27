import ErrorForbidden from '@/views/errors/Forbidden.vue';
import ErrorNotFound from '@/views/errors/NotFound.vue';
import Dashboard from '@/views/pages/Index.vue';
import DashboardContent from '@/views/pages/content/Index.vue';

const routes = [

  // // Home
  // {
  //   name: 'home',
  //   path: '/administration/',
  //   component: Dashboard,
  // },

  // Dashboard
  {
    name: 'dashboard',
    path: '/administration/',
    component: Dashboard,
  },
  // Dashboard Content
  {
    name: 'pages',
    path: '/administration/content',
    component: DashboardContent,
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