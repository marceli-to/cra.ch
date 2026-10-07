<template>
  <div>
    <Notifications />
    <SiteHeader />
    <main class="site">
      <router-view :key="route.fullPath" />
    </main>
  </div>
</template>
<script setup>
import { useRoute } from 'vue-router';
import SiteHeader from '@/views/layout/SiteHeader.vue';
import Notifications from '@/components/ui/Notifications.vue';

const route = useRoute();
</script>
