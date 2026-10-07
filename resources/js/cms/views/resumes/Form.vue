<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.periode }]">
          <label>Zeitraum</label>
          <input type="text" v-model="record.periode" @focus="clearError('periode')">
          <LabelRequired />
        </div>
        <div :class="['form-row', { 'has-error': errors.description }]">
          <label>Beschreibung</label>
          <textarea v-model="record.description" @focus="clearError('description')"></textarea>
          <LabelRequired />
        </div>
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'resumes', params: { id: record.team_member_id } }" submit />
    </form>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import Tabs from '@/components/ui/Tabs.vue';
import LabelRequired from '@/components/ui/LabelRequired.vue';
import Toggle from '@/components/ui/Toggle.vue';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const tabs = [
  { key: 'data', label: 'Daten' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

const route = useRoute();

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'resume',
  key: 'resume',
  model: () => ({
    periode: null,
    description: null,
    publish: 1,
    team_member_id: Number(route.params.teamMemberId) || null,
  }),
  redirect: resume => ({ name: 'resumes', params: { id: resume.team_member_id } }),
  titles: { create: 'Lebenslauf hinzufügen', edit: 'Lebenslauf bearbeiten' },
});
</script>
