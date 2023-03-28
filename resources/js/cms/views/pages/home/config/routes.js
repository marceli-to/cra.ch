import HomeIndex from '@/views/pages/home/Index.vue';
import HomeGrid from '@/views/pages/home/Grid.vue';

const routes = [
  {
    name: 'home',
    path: '/administration/home',
    component: HomeIndex,
  },
  {
    name: 'home-grid',
    path: '/administration/home/grid',
    component: HomeGrid,
  },

];

export default routes;