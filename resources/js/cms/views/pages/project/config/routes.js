import Dashboard from '@/views/pages/project/Index.vue';
import ProjectIndex from '@/views/pages/project/project/Index.vue';
import ProjectCreate from '@/views/pages/project/project/partials/Create.vue';
import ProjectEdit from '@/views/pages/project/project/partials/Edit.vue';
import ProjectGrid from '@/views/pages/project/project/Grid.vue';
import ProjectImages from '@/views/pages/project/images/Index.vue';
import CategoryIndex from '@/views/pages/project/category/Index.vue';
import CategoryCreate from '@/views/pages/project/category/partials/Create.vue';
import CategoryEdit from '@/views/pages/project/category/partials/Edit.vue';

const routes = [
  {
    name: 'project-overview',
    path: '/administration/project-overview',
    component: Dashboard,
  },

  {
    name: 'projects',
    path: '/administration/project/projects',
    component: ProjectIndex,
  },
  {
    name: 'project-create',
    path: '/administration/project/project/create',
    component: ProjectCreate,
  },
  {
    name: 'project-edit',
    path: '/administration/project/project/edit/:id',
    component: ProjectEdit,
  },
  {
    name: 'project-grid',
    path: '/administration/project/project/grid/:id',
    component: ProjectGrid,
  },
  {
    name: 'project-images',
    path: '/administration/project/project-images',
    component: ProjectImages,
  },
  {
    name: 'categories',
    path: '/administration/project/categories',
    component: CategoryIndex,
  },
  {
    name: 'category-create',
    path: '/administration/project/category/create',
    component: CategoryCreate,
  },
  {
    name: 'category-edit',
    path: '/administration/project/category/edit/:id',
    component: CategoryEdit,
  },

];

export default routes;