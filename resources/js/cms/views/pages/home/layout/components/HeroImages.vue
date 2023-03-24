<template>

<div :class="[isOpen ? 'is-open' : '', 'widget']" v-if="isOpen">
  <div class="widget__inner" v-if="isFetched">
    <div>
      <header>
        <h1>Bilder</h1>
        <a href="javascript:;" class="feather-icon btn-close" @click.prevent="hide()">
          <x-icon size="24"></x-icon>
        </a>
      </header>
      <div class="widget-content">
        <images 
          :imageRatioW="3" 
          :imageRatioH="2"
          :type="'HeroImage'"
          :typeId="1"
          :images="data.images">
        </images>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import { XIcon } from 'vue-feather-icons'
import Helpers from "@/mixins/Helpers";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";

export default {

  components: {
    XIcon,
    PageFooter,
    PageHeader,
    ButtonBack,
    Images
  },

  mixins: [Helpers],

  data() {
    return {

      // Data
      data: [],

      // Routes
      routes: {
        get: '/api/hero/images/home',
      },

      // States
      isLoading: false,
      isFetched: false,
      isOpen: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        saved: 'Bild gespeichert!',
        updated: 'Änderungen gespeichert!',
      }
    };
  },

  created() {
    const onEscape = (e) => {
      if (this.isOpen && e.keyCode === 27) {
        this.hide();
      }
    }
    document.addEventListener('keydown', onEscape);
    this.fetch();
  },

  methods: {
    fetch() {
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data = response.data;
        this.isFetched = true;
      });
    },
    show() {
      this.isOpen = true;
    },

    hide() {
      this.isOpen = false;
    }
  }
}
</script>