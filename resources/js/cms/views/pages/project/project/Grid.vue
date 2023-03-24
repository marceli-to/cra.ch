<template>
  <div v-if="isFetched">
    <loading-indicator v-if="isLoading"></loading-indicator>
    <grid 
      :grids="project.grids" 
      :modelName="'Project'"
      :model="project"
      @sortedRows="fetch()"
      @addedRowItem="fetch()"
      @resetItem="fetch()"
      @addedRow="fetch()"
      @deletedRow="fetch()">
    </grid>
    <page-footer>
      <button-back :route="'projects'">Zurück</button-back>
    </page-footer>
  </div>
</template>
<script>
import Grid from "@/modules/grid/Index.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";

export default {

  components: {
    Grid,
    PageFooter,
    ButtonBack
  },

  data() {
    return {

      project: {},

      // Routes
      routes: {
        get: '/api/project',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        confirm: 'Bitte löschen bestätigen!',
        updated: 'Daten aktualisiert',
      }
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      this.isLoading = true;
      this.axios.get(`${this.routes.get}/${this.$route.params.id}`).then(response => {
        this.project = response.data.project;
        this.isFetched = true;
        this.isLoading = false;
      });
    },
  }

};
</script>