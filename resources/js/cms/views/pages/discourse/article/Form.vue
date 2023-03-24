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
          <input type="text" v-model="article.title.de">
          <label-required />
        </div>
        <div class="form-row">
          <label>Text</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="article.text.de"
          ></tinymce-editor>
        </div>
        <div class="form-row">
          <label>Link</label>
          <input type="text" v-model="article.link">
        </div>

        <div class="form-row">
          <label>Thema</label>
          <div v-for="(topic, index) in topics" :key="index" class="flex mb-2x">
            <input type="checkbox" :id="`topic-${topic.id}`" :name="`topic-${topic.id}`" :value="topic.id" v-model="article.topic_ids">
            <label :for="`topic-${topic.id}`" class="ml-3x">
              {{topic.title.de}}
            </label>
          </div>
        </div>
      </div>
    </div>

    <div v-show="tabs.translation.active">
      <div>
        <div class="form-row">
          <label>Titel (en)</label>
          <input type="text" v-model="article.title.en">
        </div>
        <div class="form-row">
          <label>Text (en)</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="article.text.en"
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
        :images="article.images">
      </images>
    </div>

    <div v-show="tabs.files.active">
      <files :files="article.files"></files>
    </div>

    <div v-show="tabs.settings.active">
      <div>
        <div class="form-row">
          <radio-button 
            :label="'Publizieren?'"
            v-bind:publish.sync="article.publish"
            :model="article.publish"
            :name="'publish'">
          </radio-button>
        </div>
      </div>
    </div>

    <page-footer>
      <button-back :route="'discourse'">Zurück</button-back>
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
import tabsConfig from "@/views/pages/discourse/article/config/tabs.js";
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
      article: {
        id: null,
        title: {
          de: null,
          en: null,
        },
        text: {
          de: null,
          en: null
        },
        topic_ids: [],
        publish: 1,
        images: [],
        files: [],
      },

      topics: [],

      // Validation
      errors: {
        title: false,
      },

      // Routes
      routes: {
        get: '/api/discourse',
        store: '/api/discourse',
        update: '/api/discourse',

        getTopics: '/api/topics',
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
      this.fetchTopics();
    }
  },

  methods: {

    fetch() {
      this.isFetched = false;
      this.isLoading = true;
      this.axios.get(`${this.routes.get}/${this.$route.params.id}`).then(response => {
        this.article = response.data.article;
        this.topics = response.data.topics;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchTopics() {
      this.axios.get(`${this.routes.getTopics}`).then(response => {
        this.topics = response.data.data;
        this.isFetched = true;
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
      this.axios.post(this.routes.store, this.article).then(response => {
        this.$router.push({ name: "articles"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.article).then(response => {
        this.$router.push({ name: "articles"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Diskurs-Artikel bearbeiten" 
        : "Diskurs-Artikel hinzufügen";
    }
  }
};
</script>
