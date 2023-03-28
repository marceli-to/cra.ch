import ArticleIndex from '@/views/pages/home/article/Index.vue';
import ArticleCreate from '@/views/pages/home/article/partials/Create.vue';
import ArticleEdit from '@/views/pages/home/article/partials/Edit.vue';

const routes = [
  {
    name: 'articles',
    path: '/administration/article/index',
    component: ArticleIndex,
  },
  {
    name: 'article-create',
    path: '/administration/article/create',
    component: ArticleCreate,
  },
  {
    name: 'article-edit',
    path: '/administration/article/edit/:id',
    component: ArticleEdit,
  },
];

export default routes;