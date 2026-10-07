<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <div class="form-row" v-if="articles.length">
      <label>Artikel</label>
      <div class="select-wrapper">
        <select v-model="articleId" @change="articleId && emit('select', { article_id: articleId })">
          <option :value="null">Bitte wählen...</option>
          <option v-for="article in articles" :key="article.id" :value="article.id">{{ truncate(article.displayTitle) }}</option>
        </select>
      </div>
    </div>
    <p v-else-if="isFetched">Es sind keine publizierten Artikel vorhanden.</p>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import http from '@/lib/http';
import { truncate } from '@/lib/utils';

// Picks the article for a grid slot (home)
const emit = defineEmits(['select']);

const isLoading = ref(true);
const isFetched = ref(false);
const articles = ref([]);
const articleId = ref(null);

http.get('/api/articles/1')
  .then(({ data }) => {
    articles.value = data.data;
    isFetched.value = true;
  })
  .catch(() => {})
  .finally(() => isLoading.value = false);
</script>
