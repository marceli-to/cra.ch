<template>
  <footer class="content-footer">
    <div>
      <router-link :to="back" class="btn-secondary has-icon">
        <PhArrowLeft :size="18" weight="light" />
        <span>Zurück</span>
      </router-link>
      <button v-if="submit" type="submit" class="btn-primary has-icon">
        <PhFloppyDisk :size="18" weight="light" />
        <span>Speichern</span>
      </button>
    </div>
  </footer>
</template>
<script setup>
import { PhArrowLeft, PhFloppyDisk } from '@phosphor-icons/vue';

// Fixed footer of every page: back link, and the submit button of a form
defineProps({
  back: { type: Object, required: true },
  submit: { type: Boolean, default: false },
});
</script>
