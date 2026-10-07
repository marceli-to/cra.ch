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
          <label>Datum</label>
          <input type="text" v-model="record.date">
        </div>
        <div class="form-row">
          <label>Titel</label>
          <input type="text" v-model="record.title">
        </div>
        <div :class="['form-row', { 'has-error': errors.text }]">
          <label>Text</label>
          <Editor v-model="record.text" :has-error="!!errors.text" @focusin="clearError('text')" />
          <LabelRequired />
        </div>
        <div class="form-row">
          <label>Link</label>
          <input type="text" v-model="record.link">
        </div>
        <div class="form-row">
          <label>Linktext</label>
          <input type="text" v-model="record.linkText">
        </div>
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'articles' }" submit />
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
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const tabs = [
  { key: 'data', label: 'Text' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'article',
  key: 'article',
  model: () => ({
    date: null,
    title: null,
    text: null,
    link: null,
    linkText: null,
    publish: 1,
  }),
  redirect: { name: 'articles' },
  titles: { create: 'Artikel hinzufügen', edit: 'Artikel bearbeiten' },
});
</script>
