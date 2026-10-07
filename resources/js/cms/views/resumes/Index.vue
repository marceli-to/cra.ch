<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="listing.isFetched">
      <ContentHeader>
        <h1>Lebenslauf</h1>
        <AddButton :to="{ name: 'resume-create', params: { teamMemberId: route.params.id } }" />
      </ContentHeader>
      <SortableList v-model="listing.items" class="listing" @end="order" v-if="listing.items.length">
        <div v-for="item in listing.items" :key="item.id" :class="['listing__item is-draggable', { 'is-disabled': item.publish == 0 }]">
          <div class="listing__item-body">{{ item.periode }} – {{ item.description }}</div>
          <ListActions :record="item" edit-route="resume-edit" @toggle="listing.toggle" @destroy="listing.destroy" />
        </div>
      </SortableList>
      <p class="no-records" v-else>Es sind noch keine Daten vorhanden...</p>
      <ContentFooter :back="{ name: 'team' }" />
    </template>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import { useRoute } from 'vue-router';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import AddButton from '@/components/ui/AddButton.vue';
import ListActions from '@/components/ui/ListActions.vue';
import SortableList from '@/components/ui/SortableList.vue';
import { useOrder } from '@/composables/useOrder';
import { useListing } from '@/composables/useListing';

const route = useRoute();
const isLoading = ref(false);
const listing = reactive(useListing({ list: `/api/resumes/${route.params.id}`, resource: 'resume', isLoading }));
const order = useOrder({ url: '/api/resume/order', key: 'resumes' });
</script>
