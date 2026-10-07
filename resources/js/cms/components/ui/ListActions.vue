<template>
  <div class="listing__item-action">
    <div v-if="gridRoute">
      <router-link :to="{ name: gridRoute, params: { id: record.id } }" class="feather-icon" title="Layout">
        <PhSquaresFour :size="18" weight="light" />
      </router-link>
    </div>
    <div v-if="listRoute">
      <router-link :to="{ name: listRoute, params: { id: record.id } }" class="feather-icon" title="Lebenslauf">
        <PhListBullets :size="18" weight="light" />
      </router-link>
    </div>
    <div v-if="editRoute">
      <router-link :to="{ name: editRoute, params: { id: record.id } }" class="feather-icon" title="Bearbeiten">
        <PhPencilSimple :size="18" weight="light" />
      </router-link>
    </div>
    <div v-if="hasToggle">
      <a href="javascript:;" class="feather-icon" :title="record.publish == 1 ? 'Verbergen' : 'Publizieren'" @click.prevent="emit('toggle', record.id)">
        <PhEye v-if="record.publish == 1" :size="18" weight="light" />
        <PhEyeSlash v-else :size="18" weight="light" />
      </a>
    </div>
    <div v-if="hasCopy">
      <a href="javascript:;" class="feather-icon" title="Duplizieren" @click.prevent="emit('copy', record.id)">
        <PhCopy :size="18" weight="light" />
      </a>
    </div>
    <div v-if="hasDestroy">
      <a href="javascript:;" class="feather-icon" title="Löschen" @click.prevent="emit('destroy', record.id)">
        <PhTrash :size="18" weight="light" />
      </a>
    </div>
  </div>
</template>
<script setup>
import { PhSquaresFour, PhListBullets, PhPencilSimple, PhEye, PhEyeSlash, PhCopy, PhTrash } from '@phosphor-icons/vue';

defineProps({
  record: { type: Object, required: true },
  editRoute: { type: String, default: null },
  gridRoute: { type: String, default: null },
  listRoute: { type: String, default: null },
  hasToggle: { type: Boolean, default: true },
  hasCopy: { type: Boolean, default: false },
  hasDestroy: { type: Boolean, default: true },
});

const emit = defineEmits(['toggle', 'copy', 'destroy']);
</script>
