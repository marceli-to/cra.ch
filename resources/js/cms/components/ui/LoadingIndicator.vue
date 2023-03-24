<template>
  <div :class="'loading-indicator ' + classNames">
    <chrome-icon :size="iconSize" class="loading-indicator__icon"></chrome-icon>
  </div>
</template>
<script>
import { ChromeIcon } from 'vue-feather-icons';
export default {
  components: {
    ChromeIcon
  },
  props: {
    classNames: {
      type: String,
      default: null,
    },
    iconSize: {
      type: String,
      default: '24',
    }
  }
}
</script>
<style>
.loading-indicator {
  align-items: center;
  background-color: rgba(255,255,255,.8);
  display: flex;
  justify-content: center;
  height: 100%;
  min-height: 100px;
  left: 0;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 501;
}

.loading-indicator.is-widget {
  position: absolute;
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
