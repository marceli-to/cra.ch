<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <page-header>
      <h1>Kategorien</h1>
      <router-link :to="{ name: 'category-create'}" class="btn-add has-icon">
        <plus-icon size="16"></plus-icon>
        <span>Hinzufügen</span>
      </router-link>
    </page-header>
    <div v-if="data.length">
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
        v-for="d in data"
        :key="d.id"
        >
        <div class="listing__item-body">
          {{d.title.de}}
        </div>
        <list-actions 
          :id="d.id" 
          :record="d"
          :hasToggle="false"
          :routes="{edit: 'category-edit'}"
          @destroy="destroy($event)">
        </list-actions>
      </div>
    </div>
    <div v-else>
      <p class="no-records">{{messages.emptyData}}</p>
    </div>
    <page-footer>
      <button-back :route="'project-overview'">Zurück</button-back>
    </page-footer>
  </div>
</div>
</template>
<script>
import { PlusIcon, EditIcon, Trash2Icon } from 'vue-feather-icons';
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
    EditIcon,
    Trash2Icon,
    ButtonBack,
    PageFooter,
    PageHeader,
  },

  mixins: [Helpers],

  data() {
    return {

      data: [],

      // Routes
      routes: {
        get: '/api/categories',
        store: '/api/category',
        delete: '/api/category',
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
      this.isLoading = true;
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
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