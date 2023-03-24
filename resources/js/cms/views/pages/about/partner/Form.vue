<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <form @submit.prevent="submit" v-if="isFetched">
    <header class="content-header">
      <h1>{{title}}</h1>
    </header>
    <div>
      <div :class="[this.errors.name ? 'has-error' : '', 'form-row']">
        <label>Name *</label>
        <input type="text" v-model="data.name">
        <label-required />
      </div>
      <div class="form-row">
        <label>Webseite</label>
        <input type="text" v-model="data.url">
      </div>
    </div>
    <page-footer>
      <button-back :route="'partners'">Zurück</button-back>
      <button-submit>Speichern</button-submit>
    </page-footer>
  </form>
</div>
</template>
<script>
import ErrorHandling from "@/mixins/ErrorHandling";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";

export default {
  components: {
    ButtonBack,
    ButtonSubmit,
    LabelRequired,
    PageFooter,
    PageHeader,
  },

  mixins: [ErrorHandling],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {
        name: null,
        url: null,
        publish: 1,
      },

      // Validation
      errors: {
        name: false,
        partner_type_id: false,
      },

      // Routes
      routes: {
        get: '/api/partner',
        getTypes: '/api/partner-types',
        store: '/api/partner',
        update: '/api/partner',
      },

      // States
      isLoading: false,
      isFetched: true,
      isFetchedTypes: true,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        stored: 'Daten erfasst!',
        updated: 'Daten aktualisiert!',
      },
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
        this.$router.push({ name: "partners"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "partners"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Partner bearbeiten" 
        : "Partner hinzufügen";
    }
  }
};
</script>
