import ServiceIndex from '@/views/pages/service/Index.vue';
import ServiceCreate from '@/views/pages/service/partials/Create.vue';
import ServiceEdit from '@/views/pages/service/partials/Edit.vue';

const routes = [
  {
    name: 'services',
    path: '/administration/service/index',
    component: ServiceIndex,
  },
  {
    name: 'service-create',
    path: '/administration/service/create',
    component: ServiceCreate,
  },
  {
    name: 'service-edit',
    path: '/administration/service/edit/:id',
    component: ServiceEdit,
  },
];

export default routes;