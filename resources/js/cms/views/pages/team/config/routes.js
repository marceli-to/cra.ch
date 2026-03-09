import TeamIndex from '@/views/pages/team/Index.vue';
import TeamEdit from '@/views/pages/team/partials/Edit.vue';

const routes = [
  {
    name: 'team',
    path: '/administration/team/index',
    component: TeamIndex,
  },
  {
    name: 'team-edit',
    path: '/administration/team/edit/:id',
    component: TeamEdit,
  },
];

export default routes;
