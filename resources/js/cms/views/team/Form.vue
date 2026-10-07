<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.slug }]" v-if="type === 'create'">
          <label>Slug</label>
          <input type="text" v-model="record.slug" @focus="clearError('slug')">
          <LabelRequired />
        </div>
        <div :class="['form-row', { 'has-error': errors.title }]">
          <label>Beschreibung</label>
          <Editor v-model="record.title" :has-error="!!errors.title" @focusin="clearError('title')" />
          <LabelRequired />
        </div>
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'team' }" submit />
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
  { key: 'data', label: 'Daten' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'team-member',
  key: 'teamMember',
  model: () => ({
    slug: null,
    title: null,
    publish: 1,
  }),
  redirect: { name: 'team' },
  titles: { create: 'Team-Mitglied hinzufügen', edit: 'Team-Mitglied bearbeiten' },
});
</script>
