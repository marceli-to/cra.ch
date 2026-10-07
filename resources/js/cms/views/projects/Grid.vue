<template>
  <div>
    <LoadingIndicator v-if="isLoading && !owner" />
    <template v-if="owner">
      <Grid :owner="owner" @changed="fetch" />
      <ContentFooter :back="{ name: 'projects' }" />
    </template>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import Grid from '@/components/grid/Grid.vue';
import http from '@/lib/http';

const route = useRoute();
const isLoading = ref(false);
const owner = ref(null);

async function fetch() {
  isLoading.value = true;
  try {
    const { data } = await http.get(`/api/project/${route.params.id}`);
    owner.value = { name: 'Project', model: data.project };
  }
  catch {
    // Notified by the http error handler
  }
  finally {
    isLoading.value = false;
  }
}

fetch();
</script>
