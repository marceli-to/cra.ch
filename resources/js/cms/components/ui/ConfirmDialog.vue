<template>
  <Lightbox :open="dialog.open" :title="dialog.title" size="small" @close="answer(false)">
    <p class="confirm-dialog__text" v-if="dialog.text">{{ dialog.text }}</p>
    <template #footer>
      <!-- Enter confirms, Escape and the backdrop cancel -->
      <button type="button" class="btn-primary" autofocus @click="answer(true)">{{ dialog.action }}</button>
      <a href="javascript:;" @click.prevent="answer(false)">Abbrechen</a>
    </template>
  </Lightbox>
</template>
<script setup>
import Lightbox from '@/components/ui/Lightbox.vue';
import { dialog, answer } from '@/lib/confirm';
</script>
