<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    
    <page-header>
      <h1>Mediengalerien</h1>
      <router-link :to="{ name: 'gallery-create' }" class="btn-add has-icon">
        <plus-icon size="16"></plus-icon>
        <span>Hinzufügen</span>
      </router-link>
    </page-header>

    <draggable 
      :disabled="false"
      v-model="data" 
      @end="order(data)"
      ghost-class="draggable-ghost"
      draggable=".listing__item"
      class="listing"
      v-if="data.length">

      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item is-draggable']"
        v-for="d in data"
        :key="d.id"
      >
        <div class="listing__item-body">
          {{ d.title ? d.title : '' }} {{ !d.title && d.backend_title ? d.backend_title : '' }}
        </div>
        <list-actions 
          :id="d.id" 
          :record="d"
          :routes="{edit: 'gallery-edit'}"
          :hasDraggable="true">
        </list-actions>
      </div>
    </draggable>
    <div v-else>
      <p class="no-records">{{messages.emptyData}}</p>
    </div>
    <page-footer>
      <button-back :route="'dashboard'">Zurück</button-back>
    </page-footer>
  </div>
</div>
</template>
<script>
import { PlusIcon, EditIcon, Trash2Icon } from 'vue-feather-icons';
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
    Trash2Icon,
    ButtonBack,
    PageFooter,
    PageHeader,
    draggable
  },

  mixins: [Helpers],

  data() {
    return {

      data: [],

      // Routes
      routes: {
        get: '/api/galleries',
        store: '/api/gallery',
        delete: '/api/gallery',
        toggle: '/api/gallery/state',
        order: '/api/gallery/order',
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
        this.data = response.data.data;
        this.isFetched = true;
      });
    },

    toggle(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${id}`).then(response => {
        const index = this.data.findIndex(x => x.id === id);
        this.data[index].publish = response.data;
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

    order(data) {
      let galleries = data.map(function(gallery, index) {
        gallery.order = index;
        return gallery;
      });
      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false;
        this.axios.post(`${this.routes.order}`, {items: galleries}).then((response) => {
          this.$notify({type: 'success', text: this.messages.updated});
        });
      }.bind(this, galleries), 500);
    }, 
  }
}
</script>