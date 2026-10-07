<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.description }]">
          <label>Beschreibung</label>
          <Editor v-model="record.description" :has-error="!!errors.description" @focusin="clearError('description')" />
          <LabelRequired />
        </div>
        <div class="form-row">
          <label>Ehemalige Mitarbeitende</label>
          <Editor v-model="record.former_employees" />
        </div>
        <div class="form-row">
          <label>Projektbezogene Mitarbeitende</label>
          <Editor v-model="record.cooperation" />
        </div>
        <div class="form-row">
          <label>Mitgliedschaften</label>
          <Editor v-model="record.membership" />
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
      <ContentFooter :back="{ name: 'about' }" submit />
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
  endpoint: 'about',
  key: 'about',
  model: () => ({
    description: null,
    former_employees: null,
    cooperation: null,
    membership: null,
    publish: 1,
    images: [],
  }),
  redirect: { name: 'about' },
  titles: { create: 'Über uns hinzufügen', edit: 'Über uns bearbeiten' },
});
</script>
