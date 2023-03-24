<template>
  <div :class="[isActive == 1 ? 'is-active' : '', 'collapsible']">
    <div class="flex items-center justify-between">
      <a href="javascript:;" :class="[isActive == 1 ? 'is-active' : '', 'btn-collapse']" @click.prevent="collapse()">
        <chevron-down-icon size="20" v-if="isActive == 1"></chevron-down-icon>
        <chevron-right-icon size="20" v-else></chevron-right-icon>
        <div class="flex items-center justify-center">
          <span>{{title}}</span>
          <pill>{{count}}</pill>
        </div>
      </a>
      <slot name="button"></slot>
    </div>
    <slot name="content" v-if="isActive == 1"></slot>
  </div>
</template>

<script>
import { ChevronDownIcon, ChevronRightIcon } from 'vue-feather-icons';
import Pill from "@/components/ui/Pill.vue";
export default {

  components: {
    ChevronDownIcon,
    ChevronRightIcon,
    Pill,
  },

  props: {
    id: {
      type: String,
      default: null
    },

    title: {
      type: String,
      default: null
    },
   
    count: {
      type: Number,
      default: null
    }
  },

  data() {
    return {
      isActive: 0,
    }
  },

  created() {
    this.isActive = localStorage.getItem(`collapsible-${this.$props.id}`);
  },

  methods: {

    collapse() {
      this.isActive = this.isActive == 1 ? 0 : 1;
      localStorage.setItem(`collapsible-${this.$props.id}`, this.isActive);
    },
  }
}
</script>

<style>

</style>