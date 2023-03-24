<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <form @submit.prevent="submit" v-if="isFetched">
    <header class="content-header">
      <h1>{{title}}</h1>
    </header>
    <tabs :tabs="tabs" :errors="errors"></tabs>
    <div v-show="tabs.data.active">
      <div>
        <div :class="[this.errors.link_text ? 'has-error' : '', 'form-row']">
          <label>Linktext *</label>
          <input type="text" v-model="data.link_text">
          <label-required />
        </div>
        <div class="form-row">
          <label>Titel</label>
          <input type="text" v-model="data.title">
        </div>
        <div class="form-row">
          <label>Titel (nur Backend)</label>
          <input type="text" v-model="data.backend_title">
        </div>
        <div class="form-row">
          <label>Subtitel</label>
          <input type="text" v-model="data.subtitle">
        </div>
        <div class="form-row">
          <label>Subtitel 2. Zeile</label>
          <textarea v-model="data.text"></textarea>
        </div>
        <div class="form-row">
          <label>Mouseovertext (Lightbox Navigation)</label>
          <input type="text" v-model="data.hover_text">
        </div>
        <div class="form-row">
          <label>Credits</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="data.credits"
          ></tinymce-editor>
        </div>
      </div>
    </div>
    <div v-show="tabs.image.active">
      <images 
        :imageRatioW="4" 
        :imageRatioH="2.8"
        :images="data.images"
        :allowRatioSwitch="true">
      </images>
    </div>
    <div v-show="tabs.video.active">
      <videos :videos="data.videos"></videos>
    </div>
    <div v-show="tabs.settings.active">
      <div>
        <div class="form-row">
          <radio-button 
            :label="'Publizieren?'"
            v-bind:publish.sync="data.publish"
            :model="data.publish"
            :name="'publish'">
          </radio-button>
        </div>
      </div>
    </div>
    <page-footer>
      <button-back :route="'galleries'">Zurück</button-back>
      <button-submit>Speichern</button-submit>
    </page-footer>
  </form>
</div>
</template>
<script>
import ErrorHandling from "@/mixins/ErrorHandling";
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import RadioButton from "@/components/ui/RadioButton.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/gallery/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";
import Videos from "@/modules/videos/Index.vue";

export default {
  components: {
    RadioButton,
    ButtonBack,
    ButtonSubmit,
    LabelRequired,
    Tabs,
    TinymceEditor,
    PageFooter,
    PageHeader,
    Images,
    Videos
  },

  mixins: [ErrorHandling],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {
        title: null,
        backend_title: null,
        subtitle: null,
        text: null,
        credits: null,
        link_text: null,
        hover_text: null,
        publish: 1,
        images: [],
        videos: [],
      },

      // Validation
      errors: {
        title: false,
        link_text: false,
      },

      // Routes
      routes: {
        get: '/api/gallery',
        store: '/api/gallery',
        update: '/api/gallery',
      },

      // States
      isLoading: false,
      isFetched: true,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        stored: 'Daten erfasst!',
        updated: 'Daten aktualisiert!',
      },

      // Tabs config
      tabs: tabsConfig,

      // TinyMCE
      tinyConfig: tinyConfig,
      tinyApiKey: 'vuaywur9klvlt3excnrd9xki1a5lj25v18b2j0d0nu5tbwro',
      
    };
  },

  created() {
    if (this.$props.type == "edit") {
      this.isFetched = false;
      this.axios.get(`${this.routes.get}/${this.$route.params.id}`).then(response => {
        this.data = response.data;
        this.isFetched = true;
      });
    }
  },

  methods: {

    // Submit form
    submit() {
      if (this.$props.type == "edit") {
        this.update();
      }

      if (this.$props.type == "create") {
        this.store();
      }
    },

    store() {
      this.isLoading = true;
      this.axios.post(this.routes.store, this.data).then(response => {
        this.$router.push({ name: "galleries"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "galleries"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Galerie bearbeiten" 
        : "Galerie hinzufügen";
    }
  }
};
</script>
