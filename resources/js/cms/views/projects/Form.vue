<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.title }]">
          <label>Titel</label>
          <input type="text" v-model="record.title" @focus="clearError('title')">
          <LabelRequired />
        </div>
        <div class="form-row">
          <label>Text</label>
          <Editor v-model="record.text" />
        </div>
        <div class="form-row">
          <label>Leistungen</label>
          <Editor v-model="record.text_services" />
        </div>
        <div class="form-row">
          <label>Information</label>
          <Editor v-model="record.text_info" />
        </div>
        <div class="form-row">
          <label>Kategorie</label>
          <div v-for="category in categories" :key="category.id" class="flex mb-2x">
            <input type="checkbox" :id="`category-${category.id}`" :value="category.id" v-model="record.category_ids">
            <label :for="`category-${category.id}`" class="ml-3x">{{ category.title }}</label>
          </div>
        </div>
      </div>
      <div v-show="tab === 'worklist'">
        <div class="form-row">
          <label>Typ</label>
          <input type="text" v-model="record.type">
        </div>
        <div class="form-row">
          <label>Ort</label>
          <input type="text" v-model="record.location">
        </div>
        <div class="form-row">
          <label>Zeitraum</label>
          <input type="text" v-model="record.periode">
        </div>
        <div class="form-row">
          <label>Status</label>
          <div class="select-wrapper">
            <select v-model="record.state_id">
              <option v-for="state in states" :key="state.id" :value="state.id">{{ state.title }}</option>
            </select>
          </div>
        </div>
      </div>
      <div v-show="tab === 'images'">
        <ImageManager v-model:images="record.images" :ratios="ratios" />
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
        <div class="form-row">
          <Toggle label="Detailseite?" name="has_detail_page" v-model="record.has_detail_page" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'projects' }" submit />
    </form>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import Tabs from '@/components/ui/Tabs.vue';
import LabelRequired from '@/components/ui/LabelRequired.vue';
import Toggle from '@/components/ui/Toggle.vue';
import Editor from '@/components/ui/editor/Editor.vue';
import ImageManager from '@/components/images/ImageManager.vue';
import { useResourceForm } from '@/composables/useResourceForm';
import http from '@/lib/http';

const props = defineProps({
  type: { type: String, required: true },
});

const tabs = [
  { key: 'data', label: 'Projekt' },
  { key: 'worklist', label: 'Werkliste' },
  { key: 'images', label: 'Bilder' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

const ratios = [
  { label: 'Hoch', w: 3, h: 4 },
  { label: 'Quer', w: 16, h: 10 },
  { label: 'Quadrat', w: 1, h: 1 },
];

const categories = ref([]);
const states = ref([]);

// The edit response brings categories and states along
const load = props.type === 'create'
  ? [
    () => http.get('/api/categories').then(({ data }) => categories.value = data.data),
    () => http.get('/api/states').then(({ data }) => states.value = data.data),
  ]
  : [];

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'project',
  key: 'project',
  model: () => ({
    title: null,
    text: null,
    text_services: null,
    text_info: null,
    type: null,
    location: null,
    periode: null,
    category_ids: [],
    state_id: 1,
    publish: 1,
    has_detail_page: 0,
    images: [],
  }),
  redirect: { name: 'projects' },
  titles: { create: 'Projekt hinzufügen', edit: 'Projekt bearbeiten' },
  load,
  loaded: data => {
    categories.value = data.categories;
    states.value = data.states;
  },
});
</script>
