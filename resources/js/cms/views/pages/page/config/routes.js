import PageIndex from '@/views/pages/page/Index.vue';
import PageCreate from '@/views/pages/page/partials/Create.vue';
import PageEdit from '@/views/pages/page/partials/Edit.vue';

const routes = [
  {
    name: 'page',
    path: '/administration/page',
    component: PageIndex,
  },
  {
    name: 'page-create',
    path: '/administration/page/create',
    component: PageCreate,
  },
  {
    name: 'page-edit',
    path: '/administration/page/edit/:id',
    component: PageEdit,
  },
];

export default routes;