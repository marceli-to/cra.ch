<template>
  <router-link :to="to" class="btn-add has-icon">
    <PhPlus :size="16" weight="light" />
    <span>Hinzufügen</span>
  </router-link>
</template>
<script setup>
import { PhPlus } from '@phosphor-icons/vue';

defineProps({
  to: { type: Object, required: true },
});
</script>
