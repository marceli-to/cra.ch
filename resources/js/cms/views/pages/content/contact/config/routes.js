import ContactIndex from '@/views/pages/content/contact/Index.vue';
import ContactCreate from '@/views/pages/content/contact/partials/Create.vue';
import ContactEdit from '@/views/pages/content/contact/partials/Edit.vue';

const routes = [
  {
    name: 'contacts',
    path: '/administration/contact/index',
    component: ContactIndex,
  },
  {
    name: 'contact-create',
    path: '/administration/contact/create',
    component: ContactCreate,
  },
  {
    name: 'contact-edit',
    path: '/administration/contact/edit/:id',
    component: ContactEdit,
  },
];

export default routes;