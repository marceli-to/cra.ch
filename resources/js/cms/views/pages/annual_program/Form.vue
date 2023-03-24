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
          <label>Subtitel</label>
          <input type="text" v-model="data.subtitle">
        </div>
        <div class="form-row">
          <label>Lead</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="data.text"
          ></tinymce-editor>
        </div>
        <div class="grid-cols-12 sa-md">
          <div :class="[this.errors.periode_start ? 'has-error' : '', 'span-6']" style="position: relative">
            <label>Jahr (Start)</label>
            <the-mask
              type="text"
              mask="####"
              :masked="true"
              name="periode_start"
              v-model="data.periode_start"
            ></the-mask>
            <label-required />
          </div>
          <div :class="[this.errors.periode_end ? 'has-error' : '', 'span-6']" style="position: relative">
            <label>Jahr (Ende)</label>
            <the-mask
              type="text"
              mask="####"
              :masked="true"
              name="periode_end"
              v-model="data.periode_end"
            ></the-mask>
            <label-required />
          </div>
        </div>
        <template v-if="$props.type == 'edit'">
          <div class="form-row sb-lg">
            <page-header>
              <h3>Artikel</h3>
              <a href="javascript:;" @click="$refs.programArticleForm.show();" class="btn-add has-icon">
                <plus-icon size="16"></plus-icon>
                <span>Hinzufügen</span>
              </a>
            </page-header>
            <annual-program-articles :articles="data.articles" :programId="data.id"></annual-program-articles>
          </div>
          <div class="form-row sb-lg" v-if="data.articles_special.length">
            <page-header>
              <h3>Forum Spezial</h3>
            </page-header>
            <annual-program-articles :articles="data.articles_special" :programId="data.id"></annual-program-articles>
          </div>
          <annual-program-article-form :type="'create'" :programId="data.id" ref="programArticleForm"></annual-program-article-form>
        </template>
        <template v-else>
          <div class="sb-lg"><strong>Artikel können erst nach dem Speichern hinzugefügt werden.</strong></div>
        </template>
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
            :label="'Publizieren?'"
            v-bind:publish.sync="data.publish"
            :model="data.publish"
            :name="'publish'">
          </radio-button>
        </div>
      </div>
    </div>
    <page-footer>
      <button-back :route="'annual-programs'">Zurück</button-back>
      <button-submit>Speichern</button-submit>
    </page-footer>
  </form>
</div>
</template>
<script>
import { PlusIcon } from 'vue-feather-icons';
import { TheMask } from "vue-the-mask";
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import ErrorHandling from "@/mixins/ErrorHandling";
import RadioButton from "@/components/ui/RadioButton.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/annual_program/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";
import Files from "@/modules/files/Index.vue";
import AnnualProgramArticles from "@/views/pages/annual_program/article/Index.vue";
import AnnualProgramArticleForm from "@/views/pages/annual_program/article/Form.vue";

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
    Files,
    TinymceEditor,
    TheMask,
    AnnualProgramArticles,
    AnnualProgramArticleForm
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
        text: null,
        periode_start: null,
        periode_end: null,
        publish: 1,
        articles: [],
        articlesSpecial: [],
        images: [],
        files: [],
      },

      // Validation
      errors: {
        title: false,
        periode_start: false,
        periode_end: false,
      },

      // Routes
      routes: {
        get: '/api/annual-program',
        store: '/api/annual-program',
        update: '/api/annual-program',
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
      this.get();
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

    get() {
      this.isFetched = false;
      this.axios.get(`${this.routes.get}/${this.$route.params.id}`).then(response => {
        this.data = response.data;
        this.isFetched = true;
      });
    },

    store() {
      this.isLoading = true;
      this.axios.post(this.routes.store, this.data).then(response => {
        this.$router.push({ name: "annual-programs"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "annual-programs"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Jahresprogramm bearbeiten" 
        : "Jahresprogramm hinzufügen";
    }
  }
};
</script>
