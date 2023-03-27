import DiaryIndex from '@/views/pages/diary/Index.vue';
import DiaryCreate from '@/views/pages/diary/partials/Create.vue';
import DiaryEdit from '@/views/pages/diary/partials/Edit.vue';
import DiaryGrid from '@/views/pages/diary/Grid.vue';

const routes = [

  {
    name: 'diaries',
    path: '/administration/diaries',
    component: DiaryIndex,
  },
  {
    name: 'diary-create',
    path: '/administration/diary/create',
    component: DiaryCreate,
  },
  {
    name: 'diary-edit',
    path: '/administration/diary/edit/:id',
    component: DiaryEdit,
  },
  {
    name: 'diary-grid',
    path: '/administration/diary/grid/:id',
    component: DiaryGrid,
  },

];

export default routes;