import ResumeIndex from '@/views/pages/content/resume/Index.vue';
import ResumeCreate from '@/views/pages/content/resume/partials/Create.vue';
import ResumeEdit from '@/views/pages/content/resume/partials/Edit.vue';

const routes = [
  {
    name: 'resume',
    path: '/administration/resume/index',
    component: ResumeIndex,
  },
  {
    name: 'resume-create',
    path: '/administration/resume/create',
    component: ResumeCreate,
  },
  {
    name: 'resume-edit',
    path: '/administration/resume/edit/:id',
    component: ResumeEdit,
  },
];

export default routes;