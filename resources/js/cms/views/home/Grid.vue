<template>
  <div>
    <LoadingIndicator v-if="isLoading && !owner" />
    <Grid v-if="owner" :owner="owner" @changed="fetch" />
  </div>
</template>
<script setup>
import { ref } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import Grid from '@/components/grid/Grid.vue';
import http from '@/lib/http';

const isLoading = ref(false);
const owner = ref(null);

async function fetch() {
  isLoading.value = true;
  try {
    const { data } = await http.get('/api/home');
    owner.value = { name: 'Home', model: data.home };
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
