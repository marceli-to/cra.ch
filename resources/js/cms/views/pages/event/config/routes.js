import EventIndex from '@/views/pages/event/Index.vue';
import EventCreate from '@/views/pages/event/partials/Create.vue';
import EventEdit from '@/views/pages/event/partials/Edit.vue';

const routes = [
  {
    name: 'events',
    path: '/administration/events',
    component: EventIndex,
  },
  {
    name: 'event-create',
    path: '/administration/event/create',
    component: EventCreate,
  },
  {
    name: 'event-edit',
    path: '/administration/event/edit/:id',
    component: EventEdit,
  },
];

export default routes;