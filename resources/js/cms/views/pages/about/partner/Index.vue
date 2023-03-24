<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    
    <page-header>
      <h1>Netzwerkpartner</h1>
      <router-link :to="{ name: 'partner-create' }" class="btn-add has-icon">
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
          {{d.name}}<separator v-if="d.url" /><a :href="d.url" target="_blank" v-if="d.url">Webseite</a>
        </div>
        <list-actions 
          :id="d.id" 
          :record="d"
          :routes="{edit: 'partner-edit'}"
          :hasDraggable="true">
        </list-actions>
      </div>
    </draggable>
    <div v-else>
      <p class="no-records">{{messages.emptyData}}</p>
    </div>
    <page-footer>
      <button-back :route="'about'">Zurück</button-back>
    </page-footer>
  </div>
</div>
</template>
<script>
import { PlusIcon } from 'vue-feather-icons';
import ButtonBack from "@/components/ui/ButtonBack.vue";
import Helpers from "@/mixins/Helpers";
import ListActions from "@/components/ui/ListActions.vue";
import draggable from "vuedraggable";
import Separator from "@/components/ui/Separator.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";

export default {

  components: {
    ListActions,
    Separator,
    PlusIcon,
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
        get: '/api/partners',
        store: '/api/partner',
        delete: '/api/partner',
        toggle: '/api/partner/state',
        order: '/api/partner/order',
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
      let partners = data.map(function(partner, index) {
        partner.order = index;
        return partner;
      });
      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false;
        this.axios.post(`${this.routes.order}`, {items: partners}).then((response) => {
          this.$notify({type: 'success', text: this.messages.updated});
        });
      }.bind(this, partners), 500);
    }, 
  }
}
</script>