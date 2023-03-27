<template>
  <div class="grid-layout-selector">
    <div v-for="layout, index in $props.layouts" :key="index">
      <a href="" @click.prevent="$emit('select', {layout: layout.layout, items: layout.items})">
        <grid-layout-item :layout="layout.layout" />
      </a>
    </div>
  </div>
</template>
<script>
import GridLayoutItem from "@/modules/grid/icons/GridLayoutItem.vue";

export default {
  components: {
    GridLayoutItem
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