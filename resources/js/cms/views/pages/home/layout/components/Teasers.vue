<template>
<div :class="[isOpen ? 'is-open' : '', 'widget']" v-if="isOpen">
  <div class="widget__inner" v-if="isFetched">
    <div>
      <header>
        <h1>Teaser</h1>
        <a href="javascript:;" class="feather-icon btn-close" @click.prevent="hide()">
          <x-icon size="24"></x-icon>
        </a>
      </header>
      <div class="widget-content">
        <a 
          href="javascript:;" 
          v-for="d in data" 
          :key="d.id" 
          class="widget-item"
          @click="add(d)">
          <figure>
            <img :src="`/img/tiny/${d.image.name}`" height="100" width="100" v-if="d.image">
            <img src="/assets/img/cms/placeholder.png" height="100" width="100" v-else>
          </figure>
          <div class="flex flex-columns justify-center">
            <h2>{{ d.title | truncate(40, '...') }}</h2>
            <div v-if="d.subtitle">– {{ d.subtitle | truncate(40, '...') }}</div>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import { PlusIcon, XIcon } from 'vue-feather-icons'
import Helpers from "@/mixins/Helpers";

export default {

  components: {
    PlusIcon,
    XIcon
  },

  mixins: [Helpers],

  data() {
    return {

      // Routes
      routes: {
        get: '/api/teasers/publish'
      },

      // States
      isFetched: false,
      isOpen: false,
    }
  },

  created() {
    const onEscape = (e) => {
      if (this.isOpen && e.keyCode === 27) {
        this.hide();
      }
    }
    document.addEventListener('keydown', onEscape);
  },

  mounted() {
    this.fetch();
  },

  methods: {

    fetch() {
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
      });
    },

    add(teaser){
      this.$parent.addTeaser(teaser);
    },

    show() {
      this.isOpen = true;
    },

    hide() {
      this.isOpen = false;
    }
  }
}
</script>