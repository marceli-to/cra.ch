import GalleryIndex from '@/views/pages/gallery/Index.vue';
import GalleryCreate from '@/views/pages/gallery/partials/Create.vue';
import GalleryEdit from '@/views/pages/gallery/partials/Edit.vue';

const routes = [
  {
    name: 'galleries',
    path: '/administration/galleries',
    component: GalleryIndex,
  },
  {
    name: 'gallery-create',
    path: '/administration/gallery/create',
    component: GalleryCreate,
  },
  {
    name: 'gallery-edit',
    path: '/administration/gallery/edit/:id',
    component: GalleryEdit,
  },
];

export default routes;