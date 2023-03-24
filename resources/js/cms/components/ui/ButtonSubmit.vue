<template>
  <button type="submit" class="btn-primary has-icon">
    <download-icon size="18"></download-icon>
    <span>
      <slot />
    </span>
  </button>
</template>
<script>
import { DownloadIcon } from 'vue-feather-icons';

export default {
  
  components: {
    DownloadIcon,
  },

  props: {
    route: String,
  },
}
</script>