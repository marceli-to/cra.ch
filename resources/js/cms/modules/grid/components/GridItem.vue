<template>
  <div :class="`${$props.area} ${$props.ratio}`">
    <template v-if="$props.item.image">
      <figure>
        <img :src="getSource($props.item.image, 'cache')" height="300" width="300">
      </figure>
      <a 
        href="" 
        class="btn-delete btn-delete-item has-icon"
        @click.prevent="$emit('resetItem', $props.item.id)">
        <trash-2-icon size="16"></trash-2-icon>
        <span>Löschen</span>
      </a>
    </template>
    <template v-else-if="$props.item.article">
      <article>
        <template v-if="$props.item.article.date">
          <div>{{ $props.item.article.date }}</div>
        </template>
        <template v-if="$props.item.article.title">
          <h2 class="mb-2x">{{ $props.item.article.title }}</h2>
        </template>
        <template v-if="$props.item.article.text">
         <div v-html="$props.item.article.text"></div>
        </template>
      </article>
      <a 
        href="" 
        class="btn-delete btn-delete-item has-icon"
        @click.prevent="$emit('resetItem', $props.item.id)">
        <trash-2-icon size="16"></trash-2-icon>
        <span>Löschen</span>
      </a>
    </template>
    <template v-else>
      <div>
        <a 
          href="" 
          class="btn-select has-icon" 
          @click.prevent="$emit('showImages', {id: $props.item.id, position: $props.item.position})">
          <plus-icon size="16"></plus-icon>
          <span>Bild hinzufügen</span>
        </a>
        <template v-if="$props.hasArticles">
          <a 
            href="" 
            class="btn-select has-icon" 
            @click.prevent="$emit('showArticles', {id: $props.item.id, position: $props.item.position})">
            <plus-icon size="16"></plus-icon>
            <span>Text hinzufügen</span>
          </a>
        </template>
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

    area: {
      type: String,
      default: 'grid-area-a'
    },

    ratio: {
      type: String,
      default: 'aspect-ratio-a'
    },

    hasArticles: {
      type: Boolean,
      default: false,
    }
  },

}
</script>
