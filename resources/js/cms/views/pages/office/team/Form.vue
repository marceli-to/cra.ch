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
        <div :class="[this.errors.firstname ? 'has-error' : '', 'form-row']">
          <label>Vorname *</label>
          <input type="text" v-model="data.firstname">
          <label-required />
        </div>
        <div :class="[this.errors.name ? 'has-error' : '', 'form-row']">
          <label>Name *</label>
          <input type="text" v-model="data.name">
          <label-required />
        </div>
        <div class="form-row">
          <label>E-Mail</label>
          <input type="text" v-model="data.email">
        </div>
        <div class="form-row">
          <label>Titel</label>
          <input type="text" v-model="data.title.de">
        </div>
        <div class="form-row">
          <label>Beschreibung</label>
          <textarea v-model="data.description.de" />
        </div>
        <div class="form-row">
          <label>Lebenslauf</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="data.cv.de"
          ></tinymce-editor>
        </div>
      </div>
    </div>

    <div v-show="tabs.translation.active">
      <div>
        <div class="form-row">
          <label>Titel (en)</label>
          <input type="text" v-model="data.title.en">
        </div>
        <div class="form-row">
          <label>Beschreibung (en)</label>
          <textarea v-model="data.description.en" />
        </div>
        <div class="form-row">
          <label>Lebenslauf</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="data.cv.en"
          ></tinymce-editor>
        </div>
      </div>

    </div>

    <div v-show="tabs.images.active">
      <images 
        :allowRatioSwitch="true"
        :imageRatioW="3" 
        :imageRatioH="4"
        :ratioFormats="[{label: 'Hoch', w: 3, h: 4}]"
        :images="data.images">
      </images>
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
        <div class="form-row">
          <radio-button 
            :label="'Postum?'"
            v-bind:postum.sync="data.postum"
            :model="data.postum"
            :name="'postum'">
          </radio-button>
        </div>
      </div>
    </div>

    <page-footer>
      <button-back :route="'team-members'">Zurück</button-back>
      <button-submit>Speichern</button-submit>
    </page-footer>
  </form>
</div>
</template>
<script>
import { PlusIcon } from 'vue-feather-icons';
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import ErrorHandling from "@/mixins/ErrorHandling";
import RadioButton from "@/components/ui/RadioButton.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/office/team/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";

export default {
  components: {
    PlusIcon,
    RadioButton,
    ButtonBack,
    ButtonSubmit,
    LabelRequired,
    Tabs,
    PageFooter,
    PageHeader,
    Images,
    TinymceEditor
  },

  mixins: [ErrorHandling],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {
        id: null,
        name: null,
        firstname: null,
        email: null,
        title: {
          de: null,
          en: null,
        },
        description: {
          de: null,
          en: null
        },
        cv: {
          de: null,
          en: null
        },
        publish: 1,
        postum: 0,
        images: [],
      },


      // Validation
      errors: {
        name: false,
        firstname: false,
      },

      // Routes
      routes: {
        get: '/api/team-member',
        store: '/api/team-member',
        update: '/api/team-member',
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
      this.fetch();
    }
  },

  methods: {

    fetch() {
      this.isFetched = false;
      this.isLoading = true;
      this.axios.get(`${this.routes.get}/${this.$route.params.id}`).then(response => {
        this.data = response.data;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

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
        this.$router.push({ name: "team-members"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "team-members"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Teammitglied bearbeiten" 
        : "Teammitglied hinzufügen";
    }
  }
};
</script>
