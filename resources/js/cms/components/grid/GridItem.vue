<template>
  <div :class="`grid-area-${area} aspect-ratio-${ratio}`">
    <template v-if="item.image">
      <figure>
        <img :src="imageUrl(item.image)" height="300" width="300">
      </figure>
      <a href="" class="btn-delete btn-delete-item has-icon" @click.prevent="emit('reset', item)">
        <PhTrash :size="16" weight="light" />
        <span>Löschen</span>
      </a>
    </template>
    <template v-else-if="item.article">
      <article>
        <div v-if="item.article.date">{{ item.article.date }}</div>
        <h2 class="mb-2x" v-if="item.article.title">{{ item.article.title }}</h2>
        <div v-if="item.article.text" v-html="item.article.text"></div>
      </article>
      <a href="" class="btn-delete btn-delete-item has-icon" @click.prevent="emit('reset', item)">
        <PhTrash :size="16" weight="light" />
        <span>Löschen</span>
      </a>
    </template>
    <div v-else>
      <a href="" class="btn-select has-icon" @click.prevent="emit('select-image', item)">
        <PhPlus :size="16" weight="light" />
        <span>Bild hinzufügen</span>
      </a>
      <a v-if="articles" href="" class="btn-select has-icon" @click.prevent="emit('select-article', item)">
        <PhPlus :size="16" weight="light" />
        <span>Text hinzufügen</span>
      </a>
    </div>
  </div>
</template>
<script setup>
import { PhPlus, PhTrash } from '@phosphor-icons/vue';
import { imageUrl } from '@/lib/images';

// One slot of a grid row: its image or article, or buttons to fill it
defineProps({
  item: { type: Object, required: true },
  area: { type: String, required: true },
  ratio: { type: String, required: true },
  // the slot can take an article
  articles: { type: Boolean, default: false },
});

const emit = defineEmits(['reset', 'select-image', 'select-article']);
</script>
