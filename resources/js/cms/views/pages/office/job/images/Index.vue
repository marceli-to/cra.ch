<template>
  <div>
    <loading-indicator v-if="isLoading"></loading-indicator>
    <form @submit.prevent="submit" v-if="isFetched">
      <header class="content-header">
        <h1>Jobbilder</h1>
      </header>

      <images 
        :allowRatioSwitch="false"
        :hasPreviewState="false"
        :imageRatioW="3" 
        :imageRatioH="4"
        :ratioFormats="[{label: 'Hoch', w: 3, h: 4}]"
        :images="data.images"
        :type="'JobImage'"
        :typeId="1">
      </images>
   
      <page-footer>
        <button-back :route="'office'">Zurück</button-back>
      </page-footer>
    </form>
  </div>
</template>
<script>
import { PlusIcon } from 'vue-feather-icons';
import ErrorHandling from "@/mixins/ErrorHandling";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";

export default {
  components: {
    PlusIcon,
    ButtonBack,
    ButtonSubmit,
    PageFooter,
    PageHeader,
    Images,
  },

  mixins: [ErrorHandling],


  data() {
    return {
      
      // Model
      data: {
        images: [],
      },

      // Routes
      routes: {
        get: '/api/job-images',
      },

      // States
      isLoading: false,
      isFetched: true,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        stored: 'Daten erfasst!',
        updated: 'Daten aktualisiert!',
      },
    };
  },

  created() {
    this.fetch();
  },

  methods: {

    fetch() {
      this.isFetched = false;
      this.isLoading = true;
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data.images = response.data.images;
        this.isFetched = true;
        this.isLoading = false;
      });
    },
  },

};
</script>
  