<template>
  <div v-if="isFetched">
    <loading-indicator v-if="isLoading"></loading-indicator>
    <grid 
      :grids="diary.grids"
      :layouts="gridLayouts"
      :modelName="'Diary'"
      :model="diary"
      @sortedRows="fetch()"
      @addedRowItem="fetch()"
      @resetItem="fetch()"
      @addedRow="fetch()"
      @deletedRow="fetch()">
    </grid>
    <page-footer>
      <button-back :route="'diaries'">Zurück</button-back>
    </page-footer>
  </div>
</template>
<script>
import Grid from "@/modules/grid/Index.vue";
import GridLayouts from "@/modules/grid/config/diary.js";
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

      diary: {},

      gridLayouts: GridLayouts,

      // Routes
      routes: {
        get: '/api/diary',
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
        this.diary = response.data.diary;
        console.log(this.diary);
        this.isFetched = true;
        this.isLoading = false;
      });
    },
  }

};
</script>