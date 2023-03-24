<template>
  <div class="is-loaded">
    <page-header>
      <h1>Startseite</h1>
    </page-header>
    <div class="content content--wide cards">
      <div class="card">
        <router-link :to="{name: 'home-layout'}">
          <h2>Layout</h2>
          <p>Verwaltung der Startseite</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'teasers'}">
          <h2>Teaser</h2>
          <p>Verwaltung der Teaser</p>
        </router-link>
      </div>
    </div>
    <page-footer>
      <button-back :route="'dashboard'">Zurück</button-back>
    </page-footer>
  </div>
</template>
<script>
import Helpers from "@/mixins/Helpers";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import PageFooter from "@/components/ui/PageFooter.vue";

export default {

  components: {
    PageHeader,
    PageFooter,
    ButtonBack
  },

  mixins: [Helpers],
}
</script>