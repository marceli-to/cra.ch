import TeamIndex from '@/views/pages/team/Index.vue';
import TeamCreate from '@/views/pages/team/partials/Create.vue';
import TeamEdit from '@/views/pages/team/partials/Edit.vue';

const routes = [
  {
    name: 'team',
    path: '/administration/team/index',
    component: TeamIndex,
  },
  {
    name: 'team-create',
    path: '/administration/team/create',
    component: TeamCreate,
  },
  {
    name: 'team-edit',
    path: '/administration/team/edit/:id',
    component: TeamEdit,
  },
];

export default routes;
