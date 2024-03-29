<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <page-header>
      <h1>Tagebuch</h1>
      <router-link :to="{ name: 'diary-create'}" class="btn-add has-icon">
        <plus-icon size="16"></plus-icon>
        <span>Hinzufügen</span>
      </router-link>
    </page-header>

    <div class="listing" v-if="data.length">
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
        v-for="d in data"
        :key="d.id"
        >
        <div class="listing__item-body">
          <!-- show d.description but remove all html-tags and limit string to 100 characters -->
          <div v-html="d.description.replace(/(<([^>]+)>)/gi, '').substring(0, 100)"></div>
        </div>
        <list-actions 
          :id="d.id" 
          :record="d"
          :routes="{edit: 'diary-edit', grid: 'diary-grid'}"
          :hasGrid="true"
          @toggle="toggle($event)"
          @destroy="destroy($event)">
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
import Chip from "@/components/ui/Chip.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import draggable from 'vuedraggable';

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
    draggable,
    Chip
  },

  mixins: [Helpers],

  data() {
    return {

      data: [],

      // Routes
      routes: {
        get: '/api/diaries',
        store: '/api/diary',
        delete: '/api/diary',
        order: '/api/diarys/order',
        toggle: '/api/diary/state',
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

    destroy(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${id}`).then(response => {
          this.fetch();
          this.isLoading = false;
        });
      }
    },

    order() {
      let diarys = this.data.map(function(diary, idx) {
        diary.order = idx;
        return diary;
      });

      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false 
        this.axios.post(`${this.routes.order}`, {diarys: diarys}).then((response) => {
          this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
        });
      }.bind(this, diarys), 500);
    },
  }
}
</script>