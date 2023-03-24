<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="content content--wide is-loaded">
    <page-header>
      <h1>Layout Startseite</h1>
    </page-header>
    <page-header>
      <h2>Bilder</h2>
      <a href="javascript:;" @click="$refs.heroSelector.show();" class="btn-add has-icon">
        <plus-icon size="16"></plus-icon>
        <span>Bearbeiten</span>
      </a>
    </page-header>
    <div class="grid-items">
      <figure class="grid-item grid-item__hero">
        <img :src="getImageSrc(data.hero, 'cache')" height="300" width="300" v-if="data.hero">
      </figure>
      <page-header>
        <h2>Veranstaltungen</h2>
        <a href="javascript:;" @click="$refs.eventSelector.show();" class="btn-add has-icon">
          <plus-icon size="16"></plus-icon>
          <span>Hinzufügen</span>
        </a>
      </page-header>
      <draggable 
        :disabled="false"
        v-model="data.grid.events" 
        @end="order(data.grid.events)"
        ghost-class="draggable-ghost"
        draggable=".grid-item"
        class="grid grid-cols-12"
        v-if="data.grid.events.length">
        <div 
          class="span-6 grid-item draggable"
          v-for="item in data.grid.events"
          :key="item.id">
          <a href="javascript:;" @click.prevent="destroy(item.id)" class="btn-delete">Löschen</a>
          <figure v-if="item.event && item.event.image">
            <img :src="getImageSrc(item.event.image, 'cache')" height="300" width="200" v-if="item.event.image">
            <img src="/assets/img/cms/placeholder.png" class="aspect-3:2" width="300" height="200" v-else>
            <h2>{{item.event.title}}</h2>
            <p v-html="item.event.abstract"></p>
          </figure>
        </div>
      </draggable>
      <page-header>
        <h2>Teaser</h2>
        <a href="javascript:;" @click="$refs.teaserSelector.show();" class="btn-add has-icon">
          <plus-icon size="16"></plus-icon>
          <span>Hinzufügen</span>
        </a>
      </page-header>
      <draggable 
        :disabled="false"
        v-model="data.grid.teasers" 
        @end="order(data.grid.teasers)"
        ghost-class="draggable-ghost"
        draggable=".grid-item"
        class="grid grid-cols-12"
        v-if="data.grid.teasers.length">
        <div 
          class="span-4 grid-item draggable"
          v-for="item in data.grid.teasers"
          :key="item.id">
          <a href="javascript:;" @click.prevent="destroy(item.id)" class="btn-delete">Löschen</a>
          <figure>
            <img :src="getImageSrc(item.teaser.image, 'cache')" height="300" width="200" v-if="item.teaser.image">
            <img src="/assets/img/cms/placeholder.png" class="aspect-3:2" width="300" height="200" v-else>
            <h2>{{item.teaser.title}}</h2>
            <p v-html="item.teaser.text"></p>
          </figure>
        </div>
      </draggable>
    </div>
    <hero-selector ref="heroSelector"></hero-selector>
    <event-selector ref="eventSelector"></event-selector>
    <teaser-selector ref="teaserSelector"></teaser-selector>
    <page-footer>
      <button-back :route="'home-dashboard'">Zurück</button-back>
    </page-footer>
  </div>
</div>
</template>
<script>
import { EditIcon, PlusIcon } from 'vue-feather-icons';
import Helpers from "@/mixins/Helpers";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import draggable from "vuedraggable";
import HeroSelector from "@/views/pages/home/layout/components/HeroImages.vue";
import EventSelector from "@/views/pages/home/layout/components/Events.vue";
import TeaserSelector from "@/views/pages/home/layout/components/Teasers.vue";

export default {

  components: {
    EditIcon,
    PlusIcon,
    ButtonBack,
    PageFooter,
    PageHeader,
    draggable,
    HeroSelector,
    EventSelector,
    TeaserSelector
  },

  mixins: [Helpers],

  data() {
    return {

      data: {
        hero: {},
        grid: {},
      },

      // Routes
      routes: {
        getHero: '/api/hero/image/home',
        get: '/api/grid/items',
        storeEvent: '/api/grid/item/store/event',
        storeTeaser: '/api/grid/item/store/teaser',
        order: '/api/grid/item/order',
        delete: '/api/grid/item',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        confirm: 'Bitte löschen bestätigen!',
        updated: 'Daten aktualisiert',
      }
    }
  },

  created() {
    this.fetch();
  },

  methods: {

    fetch() {
      this.isLoading = true;
      this.axios.all([
        this.axios.get(`${this.routes.getHero}`),
        this.axios.get(`${this.routes.get}`),
      ]).then(axios.spread((...responses) => {
        this.data.hero = responses[0].data;
        this.data.grid = responses[1].data;
        this.isFetched = true;
        this.isLoading = false;
      }));
    },

    destroy(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${id}`).then(response => {
          this.fetch();
          this.isLoading = false;
        });
      }
    },

    order(data) {
      let events = data.map(function(event, index) {
        event.order = index;
        return event;
      });
      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false;
        this.axios.post(`${this.routes.order}`, {items: events}).then((response) => {
          this.$notify({type: 'success', text: this.messages.updated});
        });
      }.bind(this, events), 500);
    },

    addEvent(event) {
      this.isLoading = true;
      this.axios.post(this.routes.storeEvent, {id: event.id}).then(response => {
        this.fetch();
        this.$refs.eventSelector.hide();
        this.isLoading = false;
      });
    },

    addTeaser(teaser) {
      this.isLoading = true;
      this.axios.post(this.routes.storeTeaser, {id: teaser.id}).then(response => {
        this.fetch();
        this.$refs.teaserSelector.hide();
        this.isLoading = false;
      });
    },
  }
}
</script>