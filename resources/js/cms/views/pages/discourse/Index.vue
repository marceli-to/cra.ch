<template>
  <div class="is-loaded">
    <page-header>
      <h1>Diskurs</h1>
    </page-header>
    <div class="content content--wide cards">
      <div class="card">
        <router-link :to="{name: 'articles'}">
          <h2>Artikel</h2>
          <p>Verwaltung der Diskurs-Artikel</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'topics'}">
          <h2>Themen</h2>
          <p>Verwaltung der Diskurs-Themen</p>
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