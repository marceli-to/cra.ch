<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <form @submit.prevent="submit">
      <div :class="[this.errors.title ? 'has-error' : '', 'form-row']">
        <label>Titel *</label>
        <input type="text" v-model="video.title">
        <label-required />
      </div>
      <div :class="[this.errors.code ? 'has-error' : '', 'form-row']">
        <label>Code *</label>
        <textarea v-model="video.code"></textarea>
        <label-required />
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
          <div class="px-1x">
            <div class="mb-2x"><strong>{{d.title}}</strong></div>
            <div v-html="d.code" class="ratio-container is-16x9" style="min-width: 200px"></div>
          </div>
        </div>
        <div class="listing__item-action !items-start">
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
    videos: null,
  },

  data() {
    return {

      // Data
      data: [],

      // Link
      video: {
        title: null,
        code: null,
      },

      // Validation
      errors: {
        title: false,
        code: false,
      },

      // Routes
      routes: {
        get: '/api/videos',
        store: '/api/video',
        delete: '/api/video',
        update: '/api/video',
        toggle: '/api/video/state',
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
    if (this.$props.videos) {
      this.data = this.$props.videos;
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
      let video = {
        id: null,
        title: this.video.title,
        code: this.video.code,
        publish: 1,
        videoable_id: this.$props.typeId,
        videoable_type: this.$props.type,
      };

      this.axios.post(`${this.routes.store}`, video).then(response => {
        this.$notify({ type: "success", text: this.messages.saved });
        video.id = response.data.videoId;
        this.data.push(video);
        this.reset();
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.video.id}`, this.video).then(response => {
        this.$notify({ type: "success", text: this.messages.updated });
        this.reset();
        this.isLoading = false;
      });
    },

    edit(video) {
      this.video = video;
      this.mode = 'update';
    },

    destroy(video) {
      if (confirm("Bitte löschen bestätigen!")) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${video.id}`).then(response => {
          const index = this.data.findIndex(x => x.id === video.id);
          if (index > -1) {
            this.data.splice(index, 1);
          }
          this.isLoading = false;
        });
      }
    },

    toggle(video) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${video.id}`).then(response => {
        const index = this.data.findIndex(x => x.id === video.id);
        if (index > -1) {
          this.data[index].publish = response.data;
        }
        this.isLoading = false;
      });
    },

    reset() {
      this.video = {
        title: null,
        code: null,
      };
      this.mode = 'create';
    }

  }
}
</script>