<template>
  <div class="is-loaded">
    <page-header>
      <h1>Büro</h1>
    </page-header>
    <div class="content content--wide cards">
      <div class="card">
        <router-link :to="{name: 'team-members'}">
          <h2>Team</h2>
          <p>Verwaltung der Teammitglieder</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'team-images'}">
          <h2>Teambilder</h2>
          <p>Verwaltung der Teambilder</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'jobs'}">
          <h2>Jobs</h2>
          <p>Verwaltung der offenen Stellen</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'job-images'}">
          <h2>Jobbilder</h2>
          <p>Verwaltung der Bilder für offene Stellen</p>
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