<template>
  <div class="is-loaded">
    <page-header>
      <h1>Über uns</h1>
    </page-header>
    <div class="content content--wide cards">
      <div class="card">
        <router-link :to="{name: 'forum'}">
          <h2>Das Forum</h2>
          <p>Verwaltung des Forums</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'boards'}">
          <h2>Vorstand</h2>
          <p>Verwaltung der Vorstandsmitglieder</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'backers'}">
          <h2>Gönner</h2>
          <p>Verwaltung der Gönner</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'partners'}">
          <h2>Netzwerk</h2>
          <p>Verwaltung der Netzwerkpartner</p>
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