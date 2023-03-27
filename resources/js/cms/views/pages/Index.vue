<template>
  <div v-if="isFetched" class="is-loaded">
    <page-header>
      <h1><span>Willkommen</span> {{user.firstname}} {{user.name}}</h1>
    </page-header>
    <div class="content content--wide cards">

      <div class="card">
        <router-link :to="{name: 'home'}">
          <h2>Startseite</h2>
          <p>Verwaltung der Startseite</p>
        </router-link>
      </div>
   
      <div class="card">
        <router-link :to="{name: 'project-overview'}">
          <h2>Projekte</h2>
          <p>Verwaltung von Projekten und Kategorien</p>
        </router-link>
      </div>

      <div class="card">
        <router-link :to="{name: 'services'}">
          <h2>Leistungen</h2>
          <p>Verwaltung von Leistungen</p>
        </router-link>
      </div>

    </div>
  </div>
</template>
<script>

// Mixins
import Helpers from "@/mixins/Helpers";
import PageHeader from "@/components/ui/PageHeader.vue";

export default {

  components: {
    PageHeader,
  },


  mixins: [Helpers],

  data() {
    return {
      isFetched: false,
      user: {},
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      this.axios.get(`/api/user`).then(response => {
        this.user = response.data;
        this.isFetched = true;
      });
    }
  }
}
</script>