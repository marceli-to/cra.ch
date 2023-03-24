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
      <div class="widget-content">
        <form @submit.prevent="submit">
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
              <label>Text</label>
              <tinymce-editor
                :api-key="tinyApiKey"
                :init="tinyConfig"
                v-model="data.text"
              ></tinymce-editor>
            </div>
            <div class="form-row">
              <radio-button 
                :label="'Forum spezial?'"
                v-bind:special.sync="data.special"
                :model="data.special"
                :name="'special'">
              </radio-button>
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
import { PlusIcon, XIcon } from 'vue-feather-icons'
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import RadioButton from "@/components/ui/RadioButton.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";

export default {
  components: {
    PlusIcon,
    XIcon,
    RadioButton,
    LabelRequired,
    TinymceEditor,
    ButtonSubmit
  },

  mixins: [],

  props: {
    type: String,
    programId: null,
  },

  data() {
    return {
    
      // Model
      data: {
        id: null,
        title: null,
        subtitle: null,
        text: null,
        publish: 1,
        special: 0,
        annual_program_id: this.$props.programId
      },

      // Validation
      errors: {
        title: false,
      },

      // Routes
      routes: {
        get: '/api/annual-program-article',
        store: '/api/annual-program-article',
        update: '/api/annual-program-article',
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

      if (!this.data.title)
      {
        this.errors.title = true;
        return;
      }

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

        if (this.data.special == 0) {
          this.$parent.data.articles.push(this.data);
        }
        if (this.data.special == 1) {
          this.$parent.data.articles_special.push(this.data);
        }
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
        this.$parent.$parent.get();
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
        subtitle: null,
        text: null,
        publish: 1,
        special: 0,
        annual_program_id: this.$props.programId
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
