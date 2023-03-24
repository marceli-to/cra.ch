<template>
  <div>
    <loading-indicator v-if="isLoading"></loading-indicator>
    <div class="grid-image-selector" v-if="isFetched">
      <div>
        <a href="" class="btn-close" @click.prevent="$emit('close')">
          <x-icon size="24"></x-icon>
        </a>
        <template v-if="articles.length">
          <h2>Diskurs-Artikel wählen</h2>
          <div class="select-wrapper mt-2x px-1x">
            <select v-model="selectedArticleId" @change="$emit('select', { discourse: selectedArticleId })">
              <option :value="null">Bitte wählen...</option>
              <option v-for="a in articles" :key="a.id" :value="a.id">{{ a.title.de }}</option>
            </select>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { XIcon } from 'vue-feather-icons';
import ImageUtils from "@/modules/images/mixins/utils";

export default {

  components: {
    XIcon
  },

  mixins: [ImageUtils],

  data() {
    return {
      articles: [],
      selectedArticleId: null,

      isFetched: false,
      isLoading: false,

      routes: {
        getArticles: '/api/discourses',
      },
    }
  },

  props: {

    projectId: {
      type: Number,
      default: null,
    }
  },

  mounted() {
    this.fetchArticles();
  },

  methods: {

    fetchArticles() {
      this.isLoading = true;
      this.axios.get(`${this.routes.getArticles}`).then(response => {
        this.articles = response.data.data;
        this.isFetched = true;
        this.isLoading = false;
      });
    },
  }
}
</script>