<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">

    <div class="mb-10x" v-if="data_galleries.length">
      <h3 class="mb-3x">Bereits hinzugefügt</h3>
      <draggable 
        :disabled="false"
        v-model="data_galleries" 
        @end="order(data_galleries)"
        ghost-class="draggable-ghost"
        draggable=".listing__item"
        class="listing">
        <div
          :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item is-draggable']"
          v-for="d in data_galleries"
          :key="d.id"
        >
          <div class="listing__item-body">
            {{ d.title ? d.title : '' }} {{ !d.title && d.link_text ? d.link_text : '' }}
          </div>
          <div class="listing__item-action">
            <div>
              <a href="javascript:;" @click="remove(d)">
                <trash-2-icon size="18"></trash-2-icon>
              </a>
            </div>
          </div>
        </div>
      </draggable>
    </div>
    <div class="mb-10x" v-else>
      <p class="no-records">{{messages.emptyData}}</p>
    </div>

    <div class="listing">
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
        v-for="d in data"
        :key="d.id"
      >
        <div class="listing__item-body">
          {{ d.title ? d.title : '' }} {{ !d.title && d.link_text ? d.link_text : '' }}
        </div>
        <div class="listing__item-action">
          <div>
            <a href="javascript:;" @click="add(d)">
              <plus-icon size="18"></plus-icon>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import { PlusIcon, Trash2Icon } from 'vue-feather-icons';
import draggable from 'vuedraggable';
import Helpers from "@/mixins/Helpers";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import Separator from "@/components/ui/Separator.vue";

export default {

  components: {
    PlusIcon,
    Trash2Icon,
    LabelRequired,
    ButtonSubmit,
    Separator,
    draggable
  },

  mixins: [Helpers],

  props: {
    galleries: {
      type: Array,
      default: [],
    },
    articleId: null,
  },

  data() {
    return {

      // Data
      data: [],

      // Link
      data_galleries: [],

      // Routes
      routes: {
        get: '/api/galleries/published',
        order: '/api/gallery/order',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        saved: 'Datei gespeichert!',
        updated: 'Änderungen gespeichert!',
      },
    };
  },

  created() {
    this.data_galleries = this.$props.galleries;
    this.fetch();
  },

  methods: {

    fetch() {
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
      });
    },

    add(gallery) {
      const index = this.data_galleries.findIndex(x => x.id === gallery.id);
      if (index == -1) {
        this.data_galleries.push(gallery);
      }
    },

    remove(gallery) {
      const index = this.data_galleries.findIndex(x => x.id === gallery.id);
      if (index > -1) {
        this.data_galleries.splice(index, 1);
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