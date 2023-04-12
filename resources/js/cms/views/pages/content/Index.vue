<template>
  <div v-if="isFetched" class="is-loaded">
    <page-header>
      <h1>Weitere Seiten</h1>
    </page-header>
    <div class="content content--wide">
      <about />
      <service />
      <contact />
      <!--
      <div class="card">
        <router-link :to="{name: 'services'}">
          <h2>Leistungen</h2>
          <p>Verwaltung von Leistungen</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'contacts'}">
          <h2>Kontakt</h2>
          <p>Verwaltung der Kontaktseite</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'about'}">
          <h2>Über uns</h2>
          <p>Verwaltung der "Über uns"-Seite</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'resume'}">
          <h2>Lebenslauf</h2>
          <p>Verwaltung des Lebenslaufs</p>
        </router-link>
      </div>
      -->
      <page-footer>
        <button-back :route="'dashboard'">Zurück</button-back>
      </page-footer>
    </div>
  </div>
</template>
<script>

// Mixins
import Helpers from "@/mixins/Helpers";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import Service from "@/views/pages/content/service/Index.vue";
import About from "@/views/pages/content/about/Index.vue";
import Contact from "@/views/pages/content/contact/Index.vue";

export default {

  components: {
    PageHeader,
    PageFooter,
    ButtonBack,
    Service,
    About,
    Contact,
  },

  mixins: [Helpers],

  data() {
    return {
      isFetched: true,
      user: {},
    };
  },

  created() {
   // this.fetch();
  },

  methods: {
    // fetch() {
    //   this.axios.get(`/api/user`).then(response => {
    //     this.user = response.data;
    //     this.isFetched = true;
    //   });
    // },
    // fetch() {
    //   this.isFetched = false;
    //   this.isLoading = true;
    //   this.axios.all([
    //     this.axios.get(`${this.routes.getCategories}`),
    //     this.axios.get(`${this.routes.getStates}`),
    //   ]).then(this.axios.spread((...responses) => {
    //     this.categories = responses[0].data.data;
    //     this.states = responses[1].data.data;
    //     this.isFetched = true;
    //     this.isLoading = false;
    //   }));
    // },
  }
}
</script>