<template>
  <div v-if="isFetched" class="is-loaded">
    <page-header>
      <h1><span>Willkommen</span> {{user.firstname}} {{user.name}}</h1>
    </page-header>
    <div class="content content--wide cards">
      <div class="card">
        <router-link :to="{name: 'home-dashboard'}">
          <h2>Startseite</h2>
          <p>Verwaltung der Startseite</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'events'}">
          <h2>Veranstaltungen</h2>
          <p>Verwaltung von Kalender und Archiv</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'annual-programs'}">
          <h2>Jahresprogramm</h2>
          <p>Verwaltung des Jahresprogramms</p>
        </router-link>
      </div>

      <div class="card">
        <router-link :to="{name: 'activities'}">
          <h2>Debatten</h2>
          <p>Verwaltung der Aktivitäten</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'about'}">
          <h2>Über uns / Mitgliedschaft</h2>
          <p>Verwaltung von Über uns</p>
        </router-link>
      </div>
      <div></div>
      <div class="card">
        <router-link :to="{name: 'page'}">
          <h2>Spezialseiten</h2>
          <p>«Winterthur im Bild», «Unser Bahnhof Winterthur» oder «Ausstellungen»</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'galleries'}">
          <h2>Mediengalerien</h2>
          <p>Verwaltung aller Bildstrecken</p>
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