<template>
  <div class="grid-layout-selector">
    <a href="" @click.prevent="$emit('select', {layout: '1', items: '1'})">
      <grid-layout-item :layout="'1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '2-1', items: '2'})">
      <grid-layout-item :layout="'2-1'" />
    </a>
    <!--
    <a href="" @click.prevent="$emit('select', {layout: '1-2', items: '2'})">
      <grid-layout-item :layout="'1-2'" />
    </a>
    -->
    <a href="" @click.prevent="$emit('select', {layout: '1-1-1', items: '3'})">
      <grid-layout-item :layout="'1-1-1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1w-1w-1w', items: '3'})">
      <grid-layout-item :layout="'1w-1w-1w'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1-1', items: '2'})">
      <grid-layout-item :layout="'1-1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1w-1w', items: '2'})">
      <grid-layout-item :layout="'1w-1w'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1_1-2', items: '3'})">
      <grid-layout-item :layout="'1_1-2'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1-1_1', items: '3'})">
      <grid-layout-item :layout="'1-1_1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1-1-1_1', items: '4'})">
      <grid-layout-item :layout="'1-1-1_1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1-1_1-1', items: '4'})">
      <grid-layout-item :layout="'1-1_1-1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1_1-1-1_1', items: '5'})">
      <grid-layout-item :layout="'1_1-1-1_1'" />
    </a>
    <a href="" @click.prevent="$emit('select', {layout: '1_1-1_1-1', items: '5'})">
      <grid-layout-item :layout="'1_1-1_1-1'" />
    </a>
  </div>
</template>
<script>
import GridLayoutItem from "@/modules/grid/icons/GridLayoutItem.vue";
import ConfigProjects from "@/modules/grid/config/project.js";

export default {
  components: {
    GridLayoutItem
  },

  data() {
    return {
      projects: ConfigProjects
    }
  },

  props: {
    modelName: {
      type: String,
      default: '',
    }
  },

  mounted() {
    console.log(this.projects);
  }
}
</script>