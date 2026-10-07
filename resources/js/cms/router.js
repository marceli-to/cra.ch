import { createRouter, createWebHistory } from 'vue-router';

// Forms get type 'create' or 'edit' as a prop
const create = { type: 'create' };
const edit = { type: 'edit' };

// The login is a Blade page (/login); routes/web.php serves this app for
// every /administration URL to a logged-in admin.
const routes = [
  // Land on the most used list
  { path: '/administration', redirect: { name: 'projects' } },

  // Home
  { name: 'home-grid', path: '/administration/home', component: () => import('@/views/home/Grid.vue') },
  { name: 'articles', path: '/administration/articles', component: () => import('@/views/articles/Index.vue') },
  { name: 'article-create', path: '/administration/article/create', component: () => import('@/views/articles/Form.vue'), props: create },
  { name: 'article-edit', path: '/administration/article/edit/:id', component: () => import('@/views/articles/Form.vue'), props: edit },

  // Projects
  { name: 'projects', path: '/administration/projects', component: () => import('@/views/projects/Index.vue') },
  { name: 'project-create', path: '/administration/project/create', component: () => import('@/views/projects/Form.vue'), props: create },
  { name: 'project-edit', path: '/administration/project/edit/:id', component: () => import('@/views/projects/Form.vue'), props: edit },
  { name: 'project-grid', path: '/administration/project/grid/:id', component: () => import('@/views/projects/Grid.vue') },
  { name: 'categories', path: '/administration/categories', component: () => import('@/views/categories/Index.vue') },
  { name: 'category-create', path: '/administration/category/create', component: () => import('@/views/categories/Form.vue'), props: create },
  { name: 'category-edit', path: '/administration/category/edit/:id', component: () => import('@/views/categories/Form.vue'), props: edit },

  // Diary
  { name: 'diaries', path: '/administration/diaries', component: () => import('@/views/diaries/Index.vue') },
  { name: 'diary-create', path: '/administration/diary/create', component: () => import('@/views/diaries/Form.vue'), props: create },
  { name: 'diary-edit', path: '/administration/diary/edit/:id', component: () => import('@/views/diaries/Form.vue'), props: edit },
  { name: 'diary-grid', path: '/administration/diary/grid/:id', component: () => import('@/views/diaries/Grid.vue') },

  // Services
  { name: 'services', path: '/administration/services', component: () => import('@/views/services/Index.vue') },
  { name: 'service-create', path: '/administration/service/create', component: () => import('@/views/services/Form.vue'), props: create },
  { name: 'service-edit', path: '/administration/service/edit/:id', component: () => import('@/views/services/Form.vue'), props: edit },

  // About, team
  { name: 'about', path: '/administration/about', component: () => import('@/views/about/Index.vue') },
  { name: 'about-create', path: '/administration/about/create', component: () => import('@/views/about/Form.vue'), props: create },
  { name: 'about-edit', path: '/administration/about/edit/:id', component: () => import('@/views/about/Form.vue'), props: edit },
  { name: 'team', path: '/administration/team', component: () => import('@/views/team/Index.vue') },
  { name: 'team-create', path: '/administration/team/create', component: () => import('@/views/team/Form.vue'), props: create },
  { name: 'team-edit', path: '/administration/team/edit/:id', component: () => import('@/views/team/Form.vue'), props: edit },
  { name: 'resumes', path: '/administration/team/:id/resume', component: () => import('@/views/resumes/Index.vue') },
  { name: 'resume-create', path: '/administration/team/:teamMemberId/resume/create', component: () => import('@/views/resumes/Form.vue'), props: create },
  { name: 'resume-edit', path: '/administration/resume/edit/:id', component: () => import('@/views/resumes/Form.vue'), props: edit },

  // Contact
  { name: 'contact', path: '/administration/contact', component: () => import('@/views/contact/Index.vue') },
  { name: 'contact-create', path: '/administration/contact/create', component: () => import('@/views/contact/Form.vue'), props: create },
  { name: 'contact-edit', path: '/administration/contact/edit/:id', component: () => import('@/views/contact/Form.vue'), props: edit },

  { path: '/administration/:any(.*)', redirect: { name: 'projects' } },
];

export default createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
});
