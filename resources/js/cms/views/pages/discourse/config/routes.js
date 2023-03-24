import Dashboard from '@/views/pages/discourse/Index.vue';
import ArticleIndex from '@/views/pages/discourse/article/Index.vue';
import ArticleCreate from '@/views/pages/discourse/article/partials/Create.vue';
import ArticleEdit from '@/views/pages/discourse/article/partials/Edit.vue';
import TopicIndex from '@/views/pages/discourse/topic/Index.vue';
import TopicCreate from '@/views/pages/discourse/topic/partials/Create.vue';
import TopicEdit from '@/views/pages/discourse/topic/partials/Edit.vue';

const routes = [
  {
    name: 'discourse',
    path: '/administration/discourse',
    component: Dashboard,
  },

  {
    name: 'articles',
    path: '/administration/discourse/articles',
    component: ArticleIndex,
  },
  {
    name: 'article-create',
    path: '/administration/discourse/article/create',
    component: ArticleCreate,
  },
  {
    name: 'article-edit',
    path: '/administration/discourse/article/edit/:id',
    component: ArticleEdit,
  },

  {
    name: 'topics',
    path: '/administration/discourse/topics',
    component: TopicIndex,
  },
  {
    name: 'topic-create',
    path: '/administration/discourse/topic/create',
    component: TopicCreate,
  },
  {
    name: 'topic-edit',
    path: '/administration/discourse/topic/edit/:id',
    component: TopicEdit,
  },

];

export default routes;