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
        <div :class="[this.errors.title ? 'has-error' : '', 'form-row']">
          <label>Titel *</label>
          <input type="text" v-model="data.title">
          <label-required />
        </div>
        <div class="form-row">
          <label>Rubrik</label>
          <input type="text" v-model="data.subtitle">
        </div>
        <div class="form-row">
          <label>Vorschautext Startseite</label>
          <textarea v-model="data.abstract"></textarea>
        </div>
        <div class="form-row">
          <label>Text</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="data.text"
          ></tinymce-editor>
        </div>
        <div class="grid grid-cols-12">
          <div class="span-6">
            <label>Datum</label>
            <the-mask
              type="text"
              mask="##.##.####"
              :masked="true"
              name="date"
              v-model="data.date"
            ></the-mask>
          </div>
          <div class="span-6">
            <label>Zeit</label>
            <the-mask
              type="text"
              mask="##:##"
              :masked="true"
              name="time"
              v-model="data.time"
            ></the-mask>
          </div>
        </div>
      </div>
    </div>
    <div v-show="tabs.image.active">
      <images 
        :imageRatioW="3" 
        :imageRatioH="2"
        :images="data.images">
      </images>
    </div>
    <div v-show="tabs.file.active">
      <files :files="data.files"></files>
    </div>
    <div v-show="tabs.settings.active">
      <div>
        <div class="form-row">
          <radio-button 
            :label="'Sticky?'"
            v-bind:sticky.sync="data.sticky"
            :model="data.sticky"
            :name="'sticky'">
          </radio-button>
        </div>
        <div class="form-row">
          <radio-button 
            :label="'Platzhalter Jahresprogramm?'"
            v-bind:placeholder.sync="data.placeholder"
            :model="data.placeholder"
            :name="'placeholder'">
          </radio-button>
        </div>
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
      <button-back :route="'events'">Zurück</button-back>
      <button-submit>Speichern</button-submit>
    </page-footer>
  </form>
</div>
</template>
<script>
import { ExternalLinkIcon } from 'vue-feather-icons';
import { TheMask } from "vue-the-mask";
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import ErrorHandling from "@/mixins/ErrorHandling";
import RadioButton from "@/components/ui/RadioButton.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/event/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";
import Files from "@/modules/files/Index.vue";

export default {
  components: {
    ExternalLinkIcon,
    RadioButton,
    ButtonBack,
    ButtonSubmit,
    LabelRequired,
    Tabs,
    PageFooter,
    PageHeader,
    Images,
    Files,
    TinymceEditor,
    TheMask,
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
        subtitle: null,
        abstract: null,
        text: null,
        date: null,
        time: null,
        publish: 1,
        sticky: 0,
        placeholder: 0,
        images: [],
        files: [],
      },

      // Validation
      errors: {
        title: false,
      },

      // Routes
      routes: {
        get: '/api/event',
        store: '/api/event',
        update: '/api/event',
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
        this.$router.push({ name: "events"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "events"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Veranstaltung bearbeiten" 
        : "Veranstaltung hinzufügen";
    }
  }
};
</script>
