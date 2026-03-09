<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <form @submit.prevent="submit" v-if="isFetched">
    <header class="content-header">
      <h1>Team-Mitglied bearbeiten</h1>
    </header>
    <tabs :tabs="tabs" :errors="errors"></tabs>

    <div v-show="tabs.data.active">
      <div>
        <div :class="[this.errors.title ? 'has-error' : '', 'form-row']">
          <label>Titel</label>
          <tinymce-editor
            :init="tinyConfig"
            v-model="data.title"
          ></tinymce-editor>
        </div>
      </div>
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
      <button-back :route="'team'">Zurück</button-back>
      <button-submit>Speichern</button-submit>
    </page-footer>
  </form>
</div>
</template>
<script>
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import ErrorHandling from "@/mixins/ErrorHandling";
import RadioButton from "@/components/ui/RadioButton.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/team/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";

export default {
  components: {
    RadioButton,
    ButtonBack,
    ButtonSubmit,
    Tabs,
    PageFooter,
    TinymceEditor,
  },

  mixins: [ErrorHandling],

  data() {
    return {

      // Model
      data: {
        id: null,
        title: null,
        publish: 1,
      },

      // Validation
      errors: {
        title: false,
      },

      // Routes
      routes: {
        find: '/api/team-member',
        update: '/api/team-member',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
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
    this.fetch();
  },

  methods: {

    fetch() {
      this.isLoading = true;
      this.axios.get(`${this.routes.find}/${this.$route.params.id}`).then(response => {
        this.data = response.data.teamMember;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    submit() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "team"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },
};
</script>
