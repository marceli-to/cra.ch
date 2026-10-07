<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div class="form-row">
          <label>Projekt- und Bauleitung</label>
          <Editor v-model="record.column_one" />
        </div>
        <div class="form-row">
          <label>Leistungen / Referenzen</label>
          <Editor v-model="record.column_two" />
        </div>
      </div>
      <div v-show="tab === 'images'">
        <ImageManager v-model:images="record.images" />
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'services' }" submit />
    </form>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import Tabs from '@/components/ui/Tabs.vue';
import Toggle from '@/components/ui/Toggle.vue';
import Editor from '@/components/ui/editor/Editor.vue';
import ImageManager from '@/components/images/ImageManager.vue';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const tabs = [
  { key: 'data', label: 'Text' },
  { key: 'images', label: 'Bilder' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'service',
  key: 'service',
  model: () => ({
    column_one: null,
    column_two: null,
    publish: 1,
    images: [],
  }),
  redirect: { name: 'services' },
  titles: { create: 'Leistungen hinzufügen', edit: 'Leistungen bearbeiten' },
});
</script>
