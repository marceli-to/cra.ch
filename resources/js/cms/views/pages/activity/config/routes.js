import ActivityIndex from '@/views/pages/activity/Index.vue';
import ActivityCreate from '@/views/pages/activity/partials/Create.vue';
import ActivityEdit from '@/views/pages/activity/partials/Edit.vue';

const routes = [
  {
    name: 'activities',
    path: '/administration/activities',
    component: ActivityIndex,
  },
  {
    name: 'activity-create',
    path: '/administration/activity/create',
    component: ActivityCreate,
  },
  {
    name: 'activity-edit',
    path: '/administration/activity/edit/:id',
    component: ActivityEdit,
  },
];

export default routes;