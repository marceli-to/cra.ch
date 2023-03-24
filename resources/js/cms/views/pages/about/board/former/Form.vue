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
            <div :class="[this.errors.description ? 'has-error' : '', 'form-row']">
              <label>Beschreibung *</label>
              <input type="text" v-model="data.description">
              <label-required />
            </div>
            <div :class="[this.errors.former_board_member_type_id ? 'has-error' : '', 'form-row']">
              <div class="select-wrapper">
                <label>Bereich *</label>
                <select name="former_board_member_type" v-model="data.former_board_member_type_id">
                  <option v-for="t in types" :key="t.id" :value="t.id">{{t.description}}</option>
                </select>
              </div>
              <label-required />
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
import { tsThisType } from '@babel/types';

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
  },

  data() {
    return {
    
      // Model
      data: {
        id: null,
        description: null,
        former_board_member_type_id: 1,
      },

      types: [],

      // Validation
      errors: {
        description: false,
        former_board_member_type_id: false,
      },

      // Routes
      routes: {
        get: '/api/former-board-member',
        getTypes: '/api/former-board-member-types',
        store: '/api/former-board-member',
        update: '/api/former-board-member',
      },

      // States
      isLoading: false,
      isFetched: true,
      isFetchedTypes: false,
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
    this.getTypes();
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

    getTypes() {
      this.isFetchedTypes = false;
      this.axios.get(`${this.routes.getTypes}`).then(response => {
        this.types = response.data.data;
        this.isFetchedTypes = true;
      });
    },

    store() {
      this.isLoading = true;
      this.axios.post(this.routes.store, this.data).then(response => {
        this.$emit('formerMemberCreated');
        this.$notify({ type: "success", text: this.messages.stored });
        this.hide();
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.data.id}`, this.data).then(response => {
        this.$emit('formerMemberUpdated');
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
        description: null,
        former_board_member_type_id: null,
      };
      this.errors.description = false;
      this.errors.former_board_member_type_id = false;
    }
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Ehemaliges Mitglied bearbeiten" 
        : "Ehemaliges Mitglied hinzufügen";
    }
  }
};
</script>
