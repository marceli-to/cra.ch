import ResumeIndex from '@/views/pages/resume/Index.vue';
import ResumeCreate from '@/views/pages/resume/partials/Create.vue';
import ResumeEdit from '@/views/pages/resume/partials/Edit.vue';

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