<template>
  <div class="is-loaded">
    <page-header>
      <h1>Startseite</h1>
    </page-header>
    <div class="content content--wide cards">
      <div class="card">
        <router-link :to="{name: 'home-grid'}">
          <h2>Layout</h2>
          <p>Verwaltung des Startseiten-Layouts</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'articles'}">
          <h2>Artikel</h2>
          <p>Verwaltung der Startseiten-Artikel</p>
        </router-link>
      </div>
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
import Service from "@/views/pages/service/Index.vue";
import About from "@/views/pages/about/Index.vue";
import Contact from "@/views/pages/contact/Index.vue";

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
}
</script>