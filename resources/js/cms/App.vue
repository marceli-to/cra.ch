<template>
  <div>
    <Notifications />
    <ConfirmDialog />
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
import ConfirmDialog from '@/components/ui/ConfirmDialog.vue';

const route = useRoute();
</script>
