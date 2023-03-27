import AboutIndex from '@/views/pages/content/about/Index.vue';
import AboutCreate from '@/views/pages/content/about/partials/Create.vue';
import AboutEdit from '@/views/pages/content/about/partials/Edit.vue';

const routes = [
  {
    name: 'about',
    path: '/administration/about/index',
    component: AboutIndex,
  },
  {
    name: 'about-create',
    path: '/administration/about/create',
    component: AboutCreate,
  },
  {
    name: 'about-edit',
    path: '/administration/about/edit/:id',
    component: AboutEdit,
  },
];

export default routes;