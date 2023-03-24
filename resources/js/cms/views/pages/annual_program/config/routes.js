import AnnualProgramIndex from '@/views/pages/annual_program/Index.vue';
import AnnualProgramCreate from '@/views/pages/annual_program/partials/Create.vue';
import AnnualProgramEdit from '@/views/pages/annual_program/partials/Edit.vue';

const routes = [
  {
    name: 'annual-programs',
    path: '/administration/annual-programs',
    component: AnnualProgramIndex,
  },
  {
    name: 'annual-program-create',
    path: '/administration/annual-program/create',
    component: AnnualProgramCreate,
  },
  {
    name: 'annual-program-edit',
    path: '/administration/annual-program/edit/:id',
    component: AnnualProgramEdit,
  },
];

export default routes;