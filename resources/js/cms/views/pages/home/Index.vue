<template>
  <div v-if="isFetched">
    <loading-indicator v-if="isLoading"></loading-indicator>
    <grid 
      :grids="home.grids"
      :model="home" 
      :modelName="'Home'"
      @sortedRows="fetch()"
      @addedRowItem="fetch()"
      @resetItem="fetch()"
      @addedRow="fetch()"
      @deletedRow="fetch()">
    </grid>
  </div>
</template>
<script>
import Grid from "@/modules/grid/Index.vue";

export default {

  components: {
    Grid
  },

  data() {
    return {

      home: {},

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