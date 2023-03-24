<template>
  <div :class="[!$props.item.image ? 'flex flex-columns items-center justify-center' : '', 'grid-item']">

    <template v-if="$props.item.discourse && $props.item.discourse.published_image">
      <img :src="getSource($props.item.discourse.published_image, 'cache')" height="300" width="300">
      <a 
        href="" 
        class="btn-delete has-icon"
        @click.prevent="$emit('resetItem', $props.item.id)">
        <trash-2-icon size="16"></trash-2-icon>
        <span>Löschen</span>
      </a>
    </template>
    <template v-else-if="$props.item.image">
      <img :src="getSource($props.item.image, 'cache')" height="300" width="300">
      <a 
        href="" 
        class="btn-delete has-icon"
        @click.prevent="$emit('resetItem', $props.item.id)">
        <trash-2-icon size="16"></trash-2-icon>
        <span>Löschen</span>
      </a>
    </template>
    <template v-else>
      <div class="my-2x">
        <a 
          href="" 
          class="btn-select has-icon" 
          @click.prevent="$emit('showImages', {id: $props.item.id, position: $props.item.position})">
          <plus-icon size="16"></plus-icon>
          <span>Bild hinzufügen</span>
        </a>
      </div>
      <div class="my-2x" v-if="$props.hasArticles">
        <a 
          href="" 
          class="btn-select has-icon" 
          @click.prevent="$emit('showArticles', {id: $props.item.id, position: $props.item.position})">
          <plus-icon size="16"></plus-icon>
          <span>Artikel hinzufügen</span>
        </a>
      </div>
    </template>
  </div>
</template>
<script>
import { PlusIcon, Trash2Icon } from 'vue-feather-icons';
import ImageUtils from "@/modules/images/mixins/utils";

export default {

  components: {
    PlusIcon,
    Trash2Icon
  },

  mixins: [ImageUtils],

  props: {
    item: {
      type: Object,
      default: null
    },

    hasArticles: {
      type: Boolean,
      default: false,
    }
  },

}
</script>
