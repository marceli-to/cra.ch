<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <page-header>
      <h1>Diskurs Artikel</h1>
      <router-link :to="{ name: 'article-create'}" class="btn-add has-icon">
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
          {{d.title.de}} 
          <div class="flex ml-4x">
            <chip v-for="topic in d.topics" :key="topic.id">
              {{ topic.title.de }}
            </chip>
          </div>
        </div>
        <list-actions 
          :id="d.id" 
          :record="d"
          :routes="{edit: 'article-edit'}"
          @toggle="toggle($event)"
          @destroy="destroy($event)">
        </list-actions>
      </div>
    </draggable>
    <div v-else>
      <p class="no-records">{{messages.emptyData}}</p>
    </div>
    <page-footer>
      <button-back :route="'discourse'">Zurück</button-back>
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
        get: '/api/discourses',
        store: '/api/discourse',
        delete: '/api/discourse',
        order: '/api/discourses/order',
        toggle: '/api/discourse/state',
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
        this.isLoading = false
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
      let articles = this.data.map(function(article, idx) {
        article.order = idx;
        return article;
      });

      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false 
        this.axios.post(`${this.routes.order}`, {articles: articles}).then((response) => {
          this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
        });
      }.bind(this, articles), 500);
    },
  }
}
</script>