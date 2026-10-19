<template>
  <div>
    <!-- Small screens: logo and a button that opens the sidebar as a drawer -->
    <header class="page-header">
      <div>
        <router-link :to="{ name: 'projects' }" class="brand" title="Cristina Rutz Architekten">
          <Logo />
        </router-link>
        <button type="button" class="sidebar-toggle" title="Menü" aria-controls="sidebar" :aria-expanded="menuVisible" @click="menuVisible = true">
          <PhList :size="24" weight="light" />
        </button>
      </div>
    </header>

    <div class="sidebar-backdrop" v-if="menuVisible" @click="menuVisible = false"></div>

    <nav id="sidebar" :class="['sidebar', { 'is-open': menuVisible }]" aria-label="Hauptmenü">
      <div class="sidebar__head">
        <router-link :to="{ name: 'projects' }" class="sidebar__brand" title="Cristina Rutz Architekten">
          <Logo />
        </router-link>
        <button type="button" class="sidebar-toggle sidebar__close" title="Schliessen" @click="menuVisible = false">
          <PhX :size="24" weight="light" />
        </button>
      </div>

      <div class="sidebar__nav">
        <section v-for="group in menu" :key="group.label" class="sidebar__group">
          <h2 class="sidebar__label">{{ group.label }}</h2>
          <ul role="list">
            <li v-for="item in group.items" :key="item.route">
              <router-link :to="{ name: item.route }" :class="['sidebar__link', { 'is-active': isActive(item) }]" :aria-current="isActive(item) ? 'page' : undefined">{{ item.label }}</router-link>
            </li>
          </ul>
        </section>
      </div>

      <footer class="sidebar__user">
        <div class="sidebar__name">{{ user }}</div>
        <button type="button" class="sidebar__logout" @click="logout.submit()">
          <PhSignOut :size="16" weight="light" />
          <span>Logout</span>
        </button>
        <form method="POST" action="/logout" ref="logout" hidden>
          <input type="hidden" name="_token" :value="csrfToken">
        </form>
      </footer>
    </nav>
  </div>
</template>
<script setup>
import { ref, watch, useTemplateRef, onMounted, onBeforeUnmount } from 'vue';
import { useRoute } from 'vue-router';
import { PhList, PhX, PhSignOut } from '@phosphor-icons/vue';
import Logo from '@/components/ui/Logo.vue';
import http from '@/lib/http';

// The sidebar: always there from md up, a drawer below. Every group has a
// label; a menu item is active on its list and on the pages below it
// (forms, grids)
const menu = [
  { label: 'Startseite', items: [
    { label: 'Layout', route: 'home-grid', prefix: 'home' },
    { label: 'Artikel', route: 'articles', prefix: 'article' },
  ] },
  { label: 'Projekte', items: [
    { label: 'Projekte', route: 'projects', prefix: 'project' },
    { label: 'Kategorien', route: 'categories', prefix: 'categor' },
  ] },
  { label: 'Seiten', items: [
    { label: 'Tagebuch', route: 'diaries', prefix: 'diar' },
    { label: 'Leistungen', route: 'services', prefix: 'service' },
    { label: 'Über uns', route: 'about', prefix: 'about' },
    { label: 'Team', route: 'team', prefix: 'team' },
    { label: 'Kontakt', route: 'contact', prefix: 'contact' },
  ] },
];

const route = useRoute();
const menuVisible = ref(false);
const logout = useTemplateRef('logout');
const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
const user = ref('');

function isActive(item) {
  const name = route.name ?? '';
  return name.startsWith(item.prefix) || (item.route === 'team' && name.startsWith('resume'));
}

// Close the drawer on navigation and with Escape; the page doesn't scroll
// behind it
watch(() => route.fullPath, () => menuVisible.value = false);
watch(menuVisible, open => document.documentElement.classList.toggle('has-drawer', open));

function onKeydown(event) {
  if (event.key === 'Escape') menuVisible.value = false;
}

onMounted(() => document.addEventListener('keydown', onKeydown));
onBeforeUnmount(() => document.removeEventListener('keydown', onKeydown));

http.get('/api/user')
  .then(({ data }) => user.value = `${data.firstname} ${data.name}`)
  .catch(() => {});
</script>
