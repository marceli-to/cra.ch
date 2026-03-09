<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <page-header>
      <h2>Team</h2>
    </page-header>
    <div class="listing is-grouped" v-if="data.length">
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
        v-for="d in data"
        :key="d.id"
        >
        <div class="listing__item-body">
          <span v-html="d.title"></span>
        </div>
        <list-actions
          :id="d.id"
          :record="d"
          :routes="{edit: 'team-edit', list: 'team-resume'}"
          :hasList="true"
          :hasDestroy="false"
          @toggle="toggle($event)">
        </list-actions>
      </div>
    </div>
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
        get: '/api/team-members',
        toggle: '/api/team-member/state',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
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
        this.isLoading = false;
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
  }
}
</script>
