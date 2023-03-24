<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <form @submit.prevent="submit" v-if="isFetched && isFetchedFormer">
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
          <label>Lead</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="data.text"
          ></tinymce-editor>
        </div>

        <template v-if="$props.type == 'edit'">
          <div class="form-row sb-lg">
            <page-header>
              <h3>Mitglieder</h3>
              <a href="javascript:;" @click="$refs.boardMemberForm.show();" class="btn-add has-icon">
                <plus-icon size="16"></plus-icon>
                <span>Hinzufügen</span>
              </a>
            </page-header>
            <board-members :members="data.members" :boardId="data.id"></board-members>
          </div>
        </template>
        <template v-else>
          <div class="sb-lg"><strong>Mitglieder können erst nach dem Speichern hinzugefügt werden.</strong></div>
        </template>
        <board-member-form :type="'create'" :boardId="data.id" ref="boardMemberForm"></board-member-form>

        <template v-if="$props.type == 'edit'">
          <div class="form-row sb-lg">
            <page-header>
              <h3>Ehemalige</h3>
              <a href="javascript:;" @click="$refs.formerBoardMemberForm.show();" class="btn-add has-icon">
                <plus-icon size="16"></plus-icon>
                <span>Hinzufügen</span>
              </a>
            </page-header>
            <former-board-members :formerMembers="formerMembers" @formerMemberDestroyed="fetch();" @formerMemberUpdate="fetch()"></former-board-members>
          </div>
        </template>
        <template v-else>
          <div class="sb-lg"><strong>Ehemalige Mitglieder können erst nach dem Speichern hinzugefügt werden.</strong></div>
        </template>
        <former-board-member-form :type="'create'" ref="formerBoardMemberForm" @formerMemberCreated="fetch()" @formerMemberUpdate="fetch()"></former-board-member-form>
      </div>
    </div>
    <div v-show="tabs.images.active">
      <images 
        :imageRatioW="3" 
        :imageRatioH="2"
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
      </div>
    </div>
    <page-footer>
      <button-back :route="'boards'">Zurück</button-back>
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
import tabsConfig from "@/views/pages/about/board/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";
import BoardMembers from "@/views/pages/about/board/member/Index.vue";
import BoardMemberForm from "@/views/pages/about/board/member/Form.vue";
import FormerBoardMembers from "@/views/pages/about/board/former/Index.vue";
import FormerBoardMemberForm from "@/views/pages/about/board/former/Form.vue";

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
    BoardMembers,
    BoardMemberForm,
    FormerBoardMembers,
    FormerBoardMemberForm,
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
        title: null,
        text: null,
        publish: 1,
        images: [],
        members: [],
      },
      
      formerMembers: [],


      // Validation
      errors: {
        title: false,
      },

      // Routes
      routes: {
        get: '/api/board',
        getFormer: '/api/former-board-members',
        store: '/api/board',
        update: '/api/board',
      },

      // States
      isLoading: false,
      isFetched: true,
      isFetchedFormer: true,

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
      this.axios.get(`${this.routes.get}/${this.$route.params.id}`).then(response => {
        this.data = response.data;
        this.isFetched = true;
      });

      this.isFetchedFormer = false;
      this.axios.get(`${this.routes.getFormer}`).then(response => {
        this.formerMembers = response.data;
        this.isFetchedFormer = true;
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
        this.$router.push({ name: "boards"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "boards"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Vorstand bearbeiten" 
        : "Vorstand hinzufügen";
    }
  }
};
</script>
