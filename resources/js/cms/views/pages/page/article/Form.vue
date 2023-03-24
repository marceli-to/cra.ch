<template>
<div :class="[isOpen ? 'is-open' : '', 'widget']" v-if="isOpen">
  <div class="widget__inner" v-if="isFetched">
    <div>
      <header>
        <h1>{{title}}</h1>
        <a href="javascript:;" class="feather-icon btn-close" @click.prevent="hide()">
          <x-icon size="24"></x-icon>
        </a>
      </header>
      <div class="widget-content has-form">
        <tabs :tabs="tabs" class="is-widget" :errors="errors"></tabs>
        <form @submit.prevent="submit">
          <div>
            <div v-show="tabs.data.active">
              <div :class="[this.errors.title ? 'has-error' : '', 'form-row']">
                <label>Titel *</label>
                <input type="text" v-model="data.title">
                <label-required />
              </div>
              <div class="form-row">
                <label>Subtitel</label>
                <textarea v-model="data.subtitle" class="is-small"></textarea>
              </div>
              <div class="form-row">
                <label>Text</label>
                <tinymce-editor
                  :api-key="tinyApiKey"
                  :init="tinyConfig"
                  v-model="data.text"
                ></tinymce-editor>
              </div>
            </div>
            <div v-show="tabs.images.active">
              <images :images="data.images"></images>
            </div>
            <div v-show="tabs.galleries.active">
              <galleries :galleries="data.galleries" :articleId="data.id"></galleries>
            </div>
          </div>
          <footer>
            <button-submit>Speichern</button-submit>
          </footer>
        </form>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import { PlusIcon, XIcon } from 'vue-feather-icons';
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import Images from "@/modules/images/Index.vue";
import Galleries from "@/modules/galleries/Index.vue";
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/page/article/config/tabs.js";

export default {
  components: {
    PlusIcon,
    XIcon,
    LabelRequired,
    TinymceEditor,
    ButtonSubmit,
    Images,
    Galleries,
    Tabs
  },

  mixins: [],

  props: {
    type: String,
    pageId: null,
  },

  data() {
    return {
    
      // Model
      data: {
        id: null,
        title: null,
        subtitle: null,
        text: null,
        images: [],
        galleries: [],
        publish: 1,
        page_id: this.$props.pageId
      },

      // Validation
      errors: {
        title: false,
      },

      // Routes
      routes: {
        get: '/api/page-article',
        store: '/api/page-article',
        update: '/api/page-article',
      },

      // States
      isLoading: false,
      isFetched: true,
      isOpen: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        stored: 'Daten erfasst!',
        updated: 'Daten aktualisiert!',
      },

      // TinyMCE
      tinyConfig: tinyConfig,
      tinyApiKey: 'vuaywur9klvlt3excnrd9xki1a5lj25v18b2j0d0nu5tbwro',

      tabs: tabsConfig,
    };
  },

  created() {
    const onEscape = (e) => {
      if (this.isOpen && e.keyCode === 27) {
        this.hide();
      }
    }
    document.addEventListener('keydown', onEscape);
  },

  methods: {

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
        this.data.id = response.data.articleId;
        this.$parent.data.articles.push(this.data);
        this.$notify({ type: "success", text: this.messages.stored });
        this.hide();
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.data.id}`, this.data).then(response => {
        this.$notify({ type: "success", text: this.messages.updated });
        this.hide();
        this.isLoading = false;
      });
    },

    show(article = null) {
      if (article) {
        this.data = article;
      }
      this.isOpen = true;
    },

    hide() {
      this.reset();
      this.isOpen = false;
    },

    reset() {
      this.data = {
        id: null,
        title: null,
        text: null,
        files: [],
        links: [],
        publish: 1,
        activity_id: this.$props.activityId
      };
      this.errors.title = false;
    }
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Artikel bearbeiten" 
        : "Artikel hinzufügen";
    }
  }
};
</script>
