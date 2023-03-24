<template>
  <router-link :to="{ name: route }" class="btn-secondary has-icon">
    <arrow-left-icon size="18"></arrow-left-icon>
    <span>
      <slot />
    </span>
  </router-link>
</template>

<script>
import { ArrowLeftIcon } from 'vue-feather-icons';

export default {
  
  components: {
    ArrowLeftIcon,
  },

  props: {
    route: String,
  },
}
</script>