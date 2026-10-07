<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="listing.isFetched">
      <ContentHeader>
        <h1>Über uns</h1>
        <AddButton :to="{ name: 'about-create' }" />
      </ContentHeader>
      <div class="listing" v-if="listing.items.length">
        <div v-for="item in listing.items" :key="item.id" :class="['listing__item', { 'is-disabled': item.publish == 0 }]">
          <div class="listing__item-body">{{ truncate(item.description) }}</div>
          <ListActions :record="item" edit-route="about-edit" @toggle="listing.toggle" @destroy="listing.destroy" />
        </div>
      </div>
      <p class="no-records" v-else>Es sind noch keine Daten vorhanden...</p>
    </template>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import AddButton from '@/components/ui/AddButton.vue';
import ListActions from '@/components/ui/ListActions.vue';
import { truncate } from '@/lib/utils';
import { useListing } from '@/composables/useListing';

const isLoading = ref(false);
const listing = reactive(useListing({ list: '/api/about', resource: 'about', isLoading }));
</script>
