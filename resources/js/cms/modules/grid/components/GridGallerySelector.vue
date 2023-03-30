<template>
  <div class="grid-gallery-selector">
    <div v-for="layout, index in $props.layouts" :key="index">
      <a href="" @click.prevent="$emit('select', {layout: layout.layout, items: layout.items})">
        <grid-gallery-icon :layout="layout.layout" />
      </a>
    </div>
  </div>
</template>
<script>
import GridGalleryIcon from "@/modules/grid/icons/GridGalleryIcon.vue";

export default {
  components: {
    GridGalleryIcon
  },

  props: {
    modelName: {
      type: String,
      default: '',
    },
    layouts: {
      type: Array,
      default: () => [],
    },
  },
}
</script>