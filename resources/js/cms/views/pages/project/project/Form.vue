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
          <label>Titel </label>
          <input type="text" v-model="project.title">
          <label-required />
        </div>
        <div class="form-row">
          <label>Text</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="project.text"
          ></tinymce-editor>
        </div>
        <div class="form-row">
          <label>Leistungen</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="project.text_services"
          ></tinymce-editor>
        </div>
        <div class="form-row">
          <label>Information</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="project.text_info"
          ></tinymce-editor>
        </div>

        <div :class="[this.errors.category_ids ? 'has-error' : '', 'form-row']">
          <label>Kategorie </label>
          <div v-for="(category, index) in categories" :key="index" class="flex mb-2x">
            <input type="checkbox" :id="`category-${category.id}`" :name="`category-${category.id}`" :value="category.id" v-model="project.category_ids">
            <label :for="`category-${category.id}`" class="ml-3x">
              {{category.title}}
            </label>
          </div>
        </div>
      </div>
    </div>

    <div v-show="tabs.data_worklist.active">
      <div class="form-row">
        <label>Typ</label>
        <input type="text" v-model="project.type">
      </div>
      <div class="form-row">
        <label>Ort</label>
        <input type="text" v-model="project.location">
      </div>
      <div class="form-row">
        <label>Zeitraum</label>
        <input type="text" v-model="project.periode">
      </div>
      <div :class="[this.errors.state_id ? 'has-error' : '', 'form-row']">
        <label>Status </label>
        <div class="select-wrapper">
          <select name="state_id" v-model="project.state_id">
            <option v-for="state in states" :key="state.id" :value="state.id">
              {{state.title}}
            </option>
          </select>
        </div>
        <label-required />
      </div>
    </div>

    <div v-show="tabs.images.active">
      <images 
        :allowRatioSwitch="true"
        :imageRatioW="3" 
        :imageRatioH="4"
        :ratioFormats="[{label: 'Hoch', w: 3, h: 4}, {label: 'Quer', w: 16, h: 10}]"
        :images="project.images">
      </images>
    </div>

    <div v-show="tabs.settings.active">
      <div>
        <div class="form-row">
          <radio-button 
            :label="'Publizieren?'"
            v-bind:publish.sync="project.publish"
            :model="project.publish"
            :name="'publish'">
          </radio-button>
        </div>
        <div class="form-row">
          <radio-button 
            :label="'Detailseite?'"
            v-bind:has_detail_page.sync="project.has_detail_page"
            :model="project.has_detail_page"
            :name="'has_detail_page'">
          </radio-button>
        </div>
      </div>
    </div>

    <page-footer>
      <button-back :route="'projects'">Zurück</button-back>
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
import tabsConfig from "@/views/pages/project/project/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Files from "@/modules/files/Index.vue";
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
    Files,
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
      project: {
        id: null,
        title: null,
        text: null,
        text_services: null,
        text_info: null,
        category_ids: [],
        state_id: 1,
        publish: 1,
        has_detail_page: 0,
        images: [],
        files: [],
      },

      categories: [],
      states: [],

      // Validation
      errors: {
        title: false,
        category_ids: false,
      },

      // Routes
      routes: {
        get: '/api/project',
        store: '/api/project',
        update: '/api/project',
        getCategories: '/api/categories',
        getStates: '/api/states',
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

    if (this.$props.type == "create") {
      this.fetchCategoriesAndStates();
    }
  },

  methods: {

    fetch() {
      this.isFetched = false;
      this.isLoading = true;
      this.axios.get(`${this.routes.get}/${this.$route.params.id}`).then(response => {
        this.project = response.data.project;
        this.categories = response.data.categories;
        this.states = response.data.states;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchCategoriesAndStates() {
      this.isFetched = false;
      this.isLoading = true;
      this.axios.all([
        this.axios.get(`${this.routes.getCategories}`),
        this.axios.get(`${this.routes.getStates}`),
      ]).then(this.axios.spread((...responses) => {
        this.categories = responses[0].data.data;
        this.states = responses[1].data.data;
        this.isFetched = true;
        this.isLoading = false;
      }));
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
      this.axios.post(this.routes.store, this.project).then(response => {
        this.$router.push({ name: "projects"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.project).then(response => {
        this.$router.push({ name: "projects"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Projekt bearbeiten" 
        : "Projekt hinzufügen";
    }
  }
};
</script>
