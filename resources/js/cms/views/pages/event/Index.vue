<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    
    <page-header>
      <h1>Veranstaltungen</h1>
      <router-link :to="{ name: 'event-create' }" class="btn-add has-icon">
        <plus-icon size="16"></plus-icon>
        <span>Hinzufügen</span>
      </router-link>
    </page-header>

    <div class="listing" v-if="data.sticky.length">
      <h2 class="mb-2x">Platzhalter Sticky</h2>
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
        v-for="d in data.sticky"
        :key="d.id"
      >
        <div class="listing__item-body">
          <span class="feather-icon is-sticky" v-if="d.sticky">
            <star-icon size="16"></star-icon>
          </span>
          <span v-if="d.date">{{d.date}}<separator /></span>{{d.title}}
        </div>
        <list-actions 
          :id="d.id" 
          :record="d"
          :routes="{edit: 'event-edit'}">
        </list-actions>
      </div>
    </div>

    <div class="listing" v-if="data.placeholder.length">
      <h2 class="mb-2x">Platzhalter Jahresprogramm</h2>
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
        v-for="d in data.placeholder"
        :key="d.id"
      >
        <div class="listing__item-body">
          <span v-if="d.date">{{d.date}}<separator /></span>{{d.title}}
        </div>
        <list-actions 
          :id="d.id" 
          :record="d"
          :routes="{edit: 'event-edit'}">
        </list-actions>
      </div>
    </div>

    <div v-if="data.nonSticky">

      <div class="listing mt-5x" v-for="(data, index) in data.nonSticky" :key="index">
        <h2 class="mb-2x">{{data.year}}</h2>
        <div
          :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
          v-for="d in data.events"
          :key="d.id"
        >
          <div class="listing__item-body">
            <span v-if="d.date">{{d.date}}<separator /></span>{{d.title}}
          </div>
          <list-actions 
            :id="d.id" 
            :record="d"
            :routes="{edit: 'event-edit'}">
          </list-actions>
        </div>
      </div>
    </div>
    <page-footer>
      <button-back :route="'dashboard'">Zurück</button-back>
    </page-footer>
  </div>
</div>
</template>
<script>
import { PlusIcon, EditIcon, Trash2Icon, StarIcon } from 'vue-feather-icons';
import Helpers from "@/mixins/Helpers";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ListActions from "@/components/ui/ListActions.vue";
import Separator from "@/components/ui/Separator.vue";
import draggable from "vuedraggable";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";

export default {

  components: {
    ListActions,
    Separator,
    PlusIcon,
    EditIcon,
    StarIcon,
    Trash2Icon,
    ButtonBack,
    PageFooter,
    PageHeader,
    draggable
  },

  mixins: [Helpers],

  data() {
    return {

      data: {
        sticky: null,
        placeholder: null,
        nonSticky: null,
      },

      // Routes
      routes: {
        get: '/api/events',
        store: '/api/event',
        delete: '/api/event',
        toggle: '/api/event/state',
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
    };
  },

  created() {
    this.fetch();
  },

  methods: {

    fetch() {
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data.sticky = response.data.sticky;
        this.data.placeholder = response.data.placeholder;
        this.data.nonSticky = response.data.nonSticky;
        this.isFetched = true;
      });
    },

    toggle(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${id}`).then(response => {
        let idxSticky = this.data.sticky.findIndex(x => x.id === id);
        let idxPlaceholder = this.data.placeholder.findIndex(x => x.id === id);
        let idxNonSticky = this.data.nonSticky.findIndex(x => x.id === id);

        if (idxSticky > -1) {
          this.data.sticky[idxSticky].publish = response.data;
        }
        if (idxPlaceholder > -1) {
          this.data.placeholder[idxPlaceholder].publish = response.data;
        }
        if (idxNonSticky > -1) {
          this.data.nonSticky[idxNonSticky].publish = response.data;
        }
       
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
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
  },
}
</script>