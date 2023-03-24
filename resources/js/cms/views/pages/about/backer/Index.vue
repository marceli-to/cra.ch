<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    
    <page-header>
      <h1>Gönner</h1>
      <router-link :to="{ name: 'backer-create' }" class="btn-add has-icon">
        <plus-icon size="16"></plus-icon>
        <span>Hinzufügen</span>
      </router-link>
    </page-header>

    <div class="listing" v-if="data['Personen'] && data['Personen'].length">
      <h3 class="mb-3x"><strong>Personen</strong></h3>
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
        v-for="d in data['Personen']"
        :key="d.id"
      >
        <div class="listing__item-body">
          {{d.name}}<separator v-if="d.city" /><span v-if="d.city">{{d.city}}</span>
        </div>
        <list-actions 
          :id="d.id" 
          :record="d"
          :routes="{edit: 'backer-edit'}">
        </list-actions>
      </div>
    </div>
    <div v-else>
      <p class="no-records">{{messages.emptyData}}</p>
    </div>

    <div class="listing mt-6x" v-if="data['Firmen'] && data['Firmen'].length">
      <h3 class="mb-3x"><strong>Firmen</strong></h3>
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
        v-for="d in data['Firmen']"
        :key="d.id"
      >
        <div class="listing__item-body">
          {{d.name}}<separator v-if="d.city" /><span v-if="d.city">{{d.city}}</span>
        </div>
        <list-actions 
          :id="d.id" 
          :record="d"
          :routes="{edit: 'backer-edit'}">
        </list-actions>
      </div>
    </div>
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
    PageHeader
  },

  mixins: [Helpers],

  data() {
    return {

      data: [],

      // Routes
      routes: {
        get: '/api/backers',
        store: '/api/backer',
        delete: '/api/backer',
        toggle: '/api/backer/state',
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
        this.data = response.data;
        this.isFetched = true;
      });
    },

    toggle(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${id}`).then(response => {
        const index1 = this.data['Personen'].findIndex(x => x.id === id);
        const index2 = this.data['Firmen'].findIndex(x => x.id === id);

        if (index1 > -1) {
          this.data['Personen'][index1].publish = response.data;
        }
        if (index2 > -1) {
          this.data['Firmen'][index2].publish = response.data;
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
  }
}
</script>