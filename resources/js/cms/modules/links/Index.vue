<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <form @submit.prevent="submit">
      <div :class="[this.errors.title ? 'has-error' : '', 'form-row']">
        <label>Titel *</label>
        <input type="text" v-model="link.title">
        <label-required />
      </div>
      <div :class="[this.errors.url ? 'has-error' : '', 'form-row']">
        <label>Url *</label>
        <input type="text" v-model="link.url">
        <label-required />
      </div>
      <div class="form-row">
        <div class="select-wrapper">
          <label>Öffnen in...</label>
          <select name="target" v-model="link.target">
            <option :value="'_self'" selected>gleiches Fenster</option>
            <option :value="'_blank'">neues Fenster</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <button-submit class="is-tiny">Speichern</button-submit>
      </div>
    </form>
    <div class="listing">
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
        v-for="d in data"
        :key="d.id"
      >
        <div class="listing__item-body">
          {{d.title}}
          <separator v-if="d.url" />
          <a :href="d.url" target="_blank" v-if="d.url">{{ d.url | truncate(25, '...') }}</a>
        </div>
        <div class="listing__item-action">
          <div>
            <a href="javascript:;" @click="edit(d)">
              <edit-icon size="18"></edit-icon>
            </a>
          </div>
          <div>
            <a
              href="javascript:;"
              @click.prevent="toggle(d)"
            >
              <span v-if="d.publish" class="feather-icon">
                <eye-icon size="18"></eye-icon>
              </span>
              <span v-else>
                <eye-off-icon size="18" class="feather-icon"></eye-off-icon>
              </span>
            </a>
          </div>
          <div>
            <a
              href="javascript:;"
              class="feather-icon"
              @click.prevent="destroy(d)"
            >
              <trash2-icon size="18"></trash2-icon>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import { EyeIcon, EyeOffIcon, EditIcon, Trash2Icon } from 'vue-feather-icons';
import Helpers from "@/mixins/Helpers";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import Separator from "@/components/ui/Separator.vue";

export default {

  components: {
    EyeIcon,
    EyeOffIcon,
    EditIcon,
    Trash2Icon,
    LabelRequired,
    ButtonSubmit,
    Separator,
  },

  mixins: [Helpers],

  props: {
    typeId: null,
    type: null,
    links: null,
  },

  data() {
    return {

      // Data
      data: [],

      // Link
      link: {
        title: null,
        url: null,
        target: '_self',
      },

      // Validation
      errors: {
        title: false,
        url: false,
      },

      // Routes
      routes: {
        get: '/api/links',
        store: '/api/link',
        delete: '/api/link',
        update: '/api/link',
        toggle: '/api/link/state',
      },

      // Mode
      mode: 'create',

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Links vorhanden...',
        saved: 'Datei gespeichert!',
        updated: 'Änderungen gespeichert!',
      },
    };
  },

  created() {
    if (this.$props.links) {
      this.data = this.$props.links;
      this.isFetched = true;
    }
    else {
      this.fetch();
    }
  },

  methods: {

    fetch() {
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
      });
    },

    submit() {
      if (this.mode == 'create') {
        this.store();
      }
      if (this.mode == 'update') {
        this.update();
      }
    },

    store() {
      let link = {
        id: null,
        title: this.link.title,
        url: this.link.url,
        target: this.link.target,
        publish: 1,
        linkable_id: this.$props.typeId,
        linkable_type: this.$props.type,
      };

      this.axios.post(`${this.routes.store}`, link).then(response => {
        this.$notify({ type: "success", text: this.messages.saved });
        link.id = response.data.linkId;
        this.data.push(link);
        this.reset();
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.link.id}`, this.link).then(response => {
        this.$notify({ type: "success", text: this.messages.updated });
        this.reset();
        this.isLoading = false;
      });
    },

    edit(link) {
      this.link = link;
      this.mode = 'update';
    },

    destroy(link) {
      if (confirm("Bitte löschen bestätigen!")) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${link.id}`).then(response => {
          const index = this.data.findIndex(x => x.id === link.id);
          if (index > -1) {
            this.data.splice(index, 1);
          }
          this.isLoading = false;
        });
      }
    },

    toggle(link) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${link.id}`).then(response => {
        const index = this.data.findIndex(x => x.id === link.id);
        if (index > -1) {
          this.data[index].publish = response.data;
        }
        this.isLoading = false;
      });
    },

    reset() {
      this.link = {
        title: null,
        url: null,
        target: '_self',
      };
      this.mode = 'create';
    }

  }
}
</script>