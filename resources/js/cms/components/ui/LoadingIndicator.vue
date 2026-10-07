<template>
  <div class="loading-indicator">
    <PhCircleNotch :size="32" weight="light" class="loading-indicator__icon" />
  </div>
</template>
<script setup>
import { PhCircleNotch } from '@phosphor-icons/vue';
</script>
<style>
.loading-indicator {
  align-items: center;
  background-color: rgba(255,255,255,.8);
  display: flex;
  justify-content: center;
  height: 100%;
  left: 0;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 501;
}

.loading-indicator__icon {
  animation: rotation 1s infinite linear;
  color: #222222;
}

@keyframes rotation {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(359deg);
  }
}
</style>
