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
            <div :class="[this.errors.name ? 'has-error' : '', 'form-row']">
              <label>Name *</label>
              <input type="text" v-model="data.name">
              <label-required />
            </div>
            <div class="form-row">
              <label>Beschreibung</label>
              <tinymce-editor
                :api-key="tinyApiKey"
                :init="tinyConfig"
                v-model="data.text"
              ></tinymce-editor>
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

export default {
  components: {
    PlusIcon,
    XIcon,
    LabelRequired,
    TinymceEditor,
    ButtonSubmit,
  },

  mixins: [],

  props: {
    type: String,
    boardId: null,
  },

  data() {
    return {
    
      // Model
      data: {
        id: null,
        name: null,
        text: null,
        publish: 1,
        board_id: this.$props.boardId
      },

      // Validation
      errors: {
        name: false,
      },

      // Routes
      routes: {
        get: '/api/board-member',
        store: '/api/board-member',
        update: '/api/board-member',
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
        this.data.id = response.data.memberId;
        this.$parent.data.members.push(this.data);
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

    show(member = null) {
      if (member) {
        this.data = member;
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
        name: null,
        text: null,
        publish: 1,
        board_id: this.$props.boardId
      };
      this.errors.name = false;
    }
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Mitglied bearbeiten" 
        : "Mitglied hinzufügen";
    }
  }
};
</script>
