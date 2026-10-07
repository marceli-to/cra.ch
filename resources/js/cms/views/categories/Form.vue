<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <div>
        <div :class="['form-row', { 'has-error': errors.title }]">
          <label>Titel</label>
          <textarea v-model="record.title" @focus="clearError('title')"></textarea>
          <LabelRequired />
        </div>
      </div>
      <ContentFooter :back="{ name: 'categories' }" submit />
    </form>
  </div>
</template>
<script setup>
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import LabelRequired from '@/components/ui/LabelRequired.vue';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'category',
  key: null,
  model: () => ({
    title: null,
  }),
  redirect: { name: 'categories' },
  titles: { create: 'Kategorie hinzufügen', edit: 'Kategorie bearbeiten' },
});
</script>
