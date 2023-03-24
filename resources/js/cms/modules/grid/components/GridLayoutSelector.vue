<template>
  <div class="grid-layout-selector">
    <a href="" @click.prevent="$emit('select', {layout: '1', items: '1'})">
      <grid-layout :layout="'1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1:f', items: '1'})">
      <grid-layout :layout="'1:f'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1:w', items: '1'})">
      <grid-layout :layout="'1:w'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1:1', items: '2'})">
      <grid-layout :layout="'1:1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1:1:f', items: '2'})">
      <grid-layout :layout="'1:1:f'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '2:1', items: '3'})" v-if="$props.modelName == 'Home'">
      <grid-layout :layout="'2:1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1:2', items: '3'})" v-if="$props.modelName == 'Home'">
      <grid-layout :layout="'1:2'" />
    </a>
  </div>
</template>
<script>
import GridLayout from "@/modules/grid/icons/GridLayout.vue";

export default {
  components: {
    GridLayout
  },

  props: {
    modelName: {
      type: String,
      default: '',
    }
  },

  mounted() {
  }
}
</script>