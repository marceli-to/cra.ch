<template>
  <div v-if="isFetched">
    <loading-indicator v-if="isLoading"></loading-indicator>
    <grid 
      :grids="home.grids"
      :layouts="gridLayouts"
      :modelName="'Home'"
      :model="home" 
      @sortedRows="fetch()"
      @addedRowItem="fetch()"
      @resetItem="fetch()"
      @addedRow="fetch()"
      @deletedRow="fetch()">
    </grid>
    <page-footer>
      <button-back :route="'home'">Zurück</button-back>
    </page-footer>
  </div>
</template>
<script>
import Grid from "@/modules/grid/Index.vue";
import GridLayouts from "@/modules/grid/config/home.js";
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

      home: {},

      gridLayouts: GridLayouts,

      // Routes
      routes: {
        get: '/api/home',
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
      this.axios.get(`${this.routes.get}`).then(response => {
        this.home = response.data.home;
        this.isFetched = true;
        this.isLoading = false;
      });
    },
  }
};
</script>