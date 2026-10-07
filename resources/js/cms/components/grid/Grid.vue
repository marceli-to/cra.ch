<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <ContentHeader>
      <h1>{{ title }}</h1>
      <div class="flex">
        <a href="" class="btn-add has-icon mr-2x" @click.prevent="hasLayoutPicker = !hasLayoutPicker">
          <PhPlus :size="16" weight="light" />
          <span>Zeile hinzufügen</span>
        </a>
        <a href="" :class="['btn-move has-icon', { 'is-active': isSorting }]" @click.prevent="isSorting = !isSorting">
          <PhArrowsDownUp :size="16" weight="light" />
          <span>{{ isSorting ? 'Fertig' : 'Reihenfolge' }}</span>
        </a>
      </div>
    </ContentHeader>

    <div class="grid-gallery-selector" v-if="hasLayoutPicker">
      <div v-for="layout in choices[owner.name]" :key="layout">
        <a href="" :title="layout" @click.prevent="addRow(layout)">
          <LayoutIcon :layout="layout" />
        </a>
      </div>
    </div>

    <SortableList v-if="isSorting" v-model="rows" class="listing" @end="order">
      <div v-for="grid in rows" :key="grid.id" class="listing__item is-draggable">
        <LayoutIcon :layout="grid.layout" :items="grid.grid_items" />
      </div>
    </SortableList>

    <template v-else>
      <div v-for="grid in rows" :key="grid.id" :class="`grid-gallery grid-gallery-${grid.layout}`">
        <a href="javascript:;" class="btn-delete has-icon" @click.prevent="deleteRow(grid)">
          <PhTrash :size="16" weight="light" />
          <span>Zeile löschen</span>
        </a>
        <div>
          <div v-if="grid.layout === '1t_1-1_1-1'" class="grid-area-a aspect-ratio-c p-2x">
            <figure class="text-xs" style="flex-direction: column; align-items: flex-start;" v-html="owner.model.articleContent"></figure>
          </div>
          <template v-for="(slot, index) in layouts[grid.layout]" :key="index">
            <GridItem
              v-if="grid.grid_items[index]"
              :item="grid.grid_items[index]"
              :area="slot.area"
              :ratio="slot.ratio"
              :articles="slot.articles && owner.name === 'Home'"
              @reset="resetItem"
              @select-image="item => picker = { type: 'image', item }"
              @select-article="item => picker = { type: 'article', item }"
            />
          </template>
        </div>
      </div>
      <p class="no-records" v-if="!rows.length">Es sind noch keine Zeilen vorhanden...</p>
    </template>

    <Lightbox :open="picker?.type === 'image'" title="Bild wählen" fill @close="picker = null">
      <ImagePicker v-if="picker?.type === 'image'" :owner="owner" @select="setItem" />
    </Lightbox>
    <Lightbox :open="picker?.type === 'article'" title="Artikel wählen" size="small" @close="picker = null">
      <ArticlePicker v-if="picker?.type === 'article'" @select="setItem" />
    </Lightbox>
  </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import { PhPlus, PhTrash, PhArrowsDownUp } from '@phosphor-icons/vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import Lightbox from '@/components/ui/Lightbox.vue';
import SortableList from '@/components/ui/SortableList.vue';
import GridItem from '@/components/grid/GridItem.vue';
import LayoutIcon from '@/components/grid/LayoutIcon.vue';
import ImagePicker from '@/components/grid/ImagePicker.vue';
import ArticlePicker from '@/components/grid/ArticlePicker.vue';
import { layouts, choices } from '@/components/grid/layouts';
import { notify } from '@/lib/notify';
import http from '@/lib/http';
import { confirmDelete } from '@/lib/utils';
import { useOrder } from '@/composables/useOrder';

// The image grid of the home, a diary or a project: rows of a layout, each
// slot an image (or, on the home, an article). Every change is saved right
// away; the page reloads the owner after it ('changed').
const props = defineProps({
  // { name: 'Home' | 'Diary' | 'Project', model } (model with grids)
  owner: { type: Object, required: true },
});

const emit = defineEmits(['changed']);

const isLoading = ref(false);
const isSorting = ref(false);
const hasLayoutPicker = ref(false);

// { type: 'image' | 'article', item }
const picker = ref(null);

const title = computed(() => ({ Project: props.owner.model.title, Diary: 'Tagebuch', Home: 'Startseite' })[props.owner.name]);

// Reordered in place while sorting; replaced when the owner is reloaded
const rows = computed({
  get: () => props.owner.model.grids,
  set: list => props.owner.model.grids = list,
});

const order = useOrder({ url: '/api/grid/order', key: 'items', saved: () => emit('changed') });

async function request(call, success) {
  isLoading.value = true;
  try {
    await call();
    notify({ type: 'success', text: success });
    emit('changed');
  }
  catch {
    // Notified by the http error handler
  }
  finally {
    isLoading.value = false;
  }
}

function addRow(layout) {
  hasLayoutPicker.value = false;
  const grid = {
    layout,
    items: layouts[layout].length,
    model: { id: props.owner.model.id, name: props.owner.name },
  };
  request(() => http.post('/api/grid', grid), 'Zeile hinzugefügt');
}

function deleteRow(grid) {
  if (confirmDelete()) {
    request(() => http.delete(`/api/grid/${grid.id}`), 'Zeile gelöscht');
  }
}

function resetItem(item) {
  if (confirmDelete()) {
    request(() => http.put(`/api/grid-item/${item.id}`), 'Eintrag gelöscht');
  }
}

// selection: { image_id, project_id?, page? } or { article_id }
function setItem(selection) {
  const { item } = picker.value;
  picker.value = null;
  request(() => http.post('/api/grid-item', { id: item.id, position: item.position, ...selection }), 'Eintrag gespeichert');
}
</script>
