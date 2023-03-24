import Dashboard from '@/views/pages/office/Index.vue';
import TeamMemberIndex from '@/views/pages/office/team/Index.vue';
import TeamMemberCreate from '@/views/pages/office/team/partials/Create.vue';
import TeamMemberEdit from '@/views/pages/office/team/partials/Edit.vue';
import TeamImages from '@/views/pages/office/team/images/Index.vue';

import JobIndex from '@/views/pages/office/job/Index.vue';
import JobCreate from '@/views/pages/office/job/partials/Create.vue';
import JobEdit from '@/views/pages/office/job/partials/Edit.vue';
import JobImages from '@/views/pages/office/job/images/Index.vue';

const routes = [
  {
    name: 'office',
    path: '/administration/office',
    component: Dashboard,
  },
  {
    name: 'team-members',
    path: '/administration/office/team-members',
    component: TeamMemberIndex,
  },
  {
    name: 'team-member-create',
    path: '/administration/office/team-member/create',
    component: TeamMemberCreate,
  },
  {
    name: 'team-member-edit',
    path: '/administration/office/team-member/edit/:id',
    component: TeamMemberEdit,
  },

  {
    name: 'team-images',
    path: '/administration/office/team-images',
    component: TeamImages,
  },

  {
    name: 'jobs',
    path: '/administration/office/jobs',
    component: JobIndex,
  },
  {
    name: 'job-create',
    path: '/administration/office/job/create',
    component: JobCreate,
  },
  {
    name: 'job-edit',
    path: '/administration/office/job/edit/:id',
    component: JobEdit,
  },

  {
    name: 'job-images',
    path: '/administration/office/job-images',
    component: JobImages,
  },


];

export default routes;