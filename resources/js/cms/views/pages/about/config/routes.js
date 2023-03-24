import Dashboard from '@/views/pages/about/Index.vue';
import BackerIndex from '@/views/pages/about/backer/Index.vue';
import BackerCreate from '@/views/pages/about/backer/partials/Create.vue';
import BackerEdit from '@/views/pages/about/backer/partials/Edit.vue';

import PartnerIndex from '@/views/pages/about/partner/Index.vue';
import PartnerCreate from '@/views/pages/about/partner/partials/Create.vue';
import PartnerEdit from '@/views/pages/about/partner/partials/Edit.vue';

import ForumIndex from '@/views/pages/about/forum/Index.vue';
import ForumCreate from '@/views/pages/about/forum/partials/Create.vue';
import ForumEdit from '@/views/pages/about/forum/partials/Edit.vue';

import BoardIndex from '@/views/pages/about/board/Index.vue';
import BoardCreate from '@/views/pages/about/board/partials/Create.vue';
import BoardEdit from '@/views/pages/about/board/partials/Edit.vue';

const routes = [
  {
    name: 'about',
    path: '/administration/about',
    component: Dashboard,
  },
  {
    name: 'backers',
    path: '/administration/about/backers',
    component: BackerIndex,
  },
  {
    name: 'backer-create',
    path: '/administration/about/backer/create',
    component: BackerCreate,
  },
  {
    name: 'backer-edit',
    path: '/administration/about/backer/edit/:id',
    component: BackerEdit,
  },

  {
    name: 'partners',
    path: '/administration/about/partners',
    component: PartnerIndex,
  },
  {
    name: 'partner-create',
    path: '/administration/about/partner/create',
    component: PartnerCreate,
  },
  {
    name: 'partner-edit',
    path: '/administration/about/partner/edit/:id',
    component: PartnerEdit,
  },
  
  {
    name: 'forum',
    path: '/administration/about/forum',
    component: ForumIndex,
  },
  {
    name: 'forum-create',
    path: '/administration/about/forum/create',
    component: ForumCreate,
  },
  {
    name: 'forum-edit',
    path: '/administration/about/forum/edit/:id',
    component: ForumEdit,
  },

  {
    name: 'boards',
    path: '/administration/about/boards',
    component: BoardIndex,
  },
  {
    name: 'board-create',
    path: '/administration/about/board/create',
    component: BoardCreate,
  },
  {
    name: 'board-edit',
    path: '/administration/about/board/edit/:id',
    component: BoardEdit,
  },
];

export default routes;