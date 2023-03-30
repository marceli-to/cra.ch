<template>
  <div>
    <loading-indicator v-if="isLoading"></loading-indicator>
    <div class="grid-asset-selector" v-if="isFetched">
      <div>
        <a href="" class="btn-close" @click.prevent="$emit('close')">
          <x-icon size="24"></x-icon>
        </a>
        <template v-if="project">
          <h2>{{ project.title }}</h2>
          <div class="grid-asset-selector__images">
            <figure v-for="image in project.images" :key="image.id">
              <img 
                :src="getSource(image, 'cache')" 
                height="300" 
                width="300" v-if="image"
                @click="$emit('select', {image: image.id, project: project.id})" />
            </figure>
          </div>        
        </template>
        <template v-if="diary">
          <h2>{{ diary.title }}</h2>
          <div class="grid-asset-selector__images" v-if="diary.images.length > 0">
            <figure v-for="image in diary.images" :key="image.id">
              <img 
                :src="getSource(image, 'cache')" 
                height="300" 
                width="300" v-if="image"
                @click="$emit('select', {image: image.id})" />
            </figure>
          </div>   
          <div v-else>Es sind keine Bilder für dieses Tagebuch vorhanden.</div>     
        </template>
        <template v-if="projects.length">
          <h2>Projekt wählen</h2>
          <div class="select-wrapper mt-3x">
            <select v-model="selectedProjectId" @change="selectProjectImages()">
              <option :value="null">Bitte wählen...</option>
              <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option>
            </select>
          </div>
          <div class="grid-asset-selector__images mt-2x" v-if="selectedProjectId">
            <template v-if="selectedProject.images.length">
              <figure v-for="image in selectedProject.images" :key="image.id">
                <img 
                  :src="getSource(image, 'cache')" 
                  height="300" 
                  width="300" v-if="image"
                  @click="$emit('select', {image: image.id, project: selectedProject.id})" />
              </figure>
            </template>
            <template v-else>
              Es sind keine Bilder für dieses Projekt vorhanden.
            </template>
          </div>        
        </template>
        <template v-if="articles.length">
          <h2 class="mt-6x">Artikel wählen</h2>
          <div class="select-wrapper mt-3x">
            <select v-model="selectedArticleId" @change="selectedArticleImages()">
              <option :value="null">Bitte wählen...</option>
              <option v-for="a in articles" :key="a.id" :value="a.id" v-html="a.displayTitle"></option>
            </select>
          </div>
          <div class="grid-asset-selector__images mt-2x" v-if="selectedArticleId">
            <template v-if="selectedArticle.images.length">
              <figure v-for="image in selectedArticle.images" :key="image.id">
                <img 
                  :src="getSource(image, 'cache')" 
                  height="300" 
                  width="300" v-if="image"
                  @click="$emit('select', {image: image.id})" />
              </figure>
            </template>
            <template v-else>
              Es sind keine Bilder für diesen Artikel vorhanden.
            </template>
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
      project: null,
      diary: null,
      projects: [],
      articles: [],
      selectedProjectId: null,
      selectedArticleId: null,
      selectedProject: [],
      selectedArticle: [],

      isFetched: false,
      isLoading: false,

      routes: {
        getProject: '/api/project',
        getDiary: '/api/diary',
        getProjects: '/api/projects/1',
        getArticles: '/api/articles/1',
      },
    }
  },

  props: {

    model: {
      type: Object,
    },
    modelName: {
      type: String,
    }
  },

  mounted() {
    if (this.$props.modelName == 'Project') {
      this.fetchProject(this.$props.model.id);
    }
    else if (this.$props.modelName == 'Diary') {
      this.fetchDiary(this.$props.model.id);
    }
    else {
      this.fetchProjectsAndArticles();
    }
  },

  methods: {

    fetchProject(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.getProject}/${id}`).then(response => {
        this.project = response.data.project;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchDiary(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.getDiary}/${id}`).then(response => {
        this.diary = response.data.diary;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchProjectsAndArticles() {
      this.isLoading = true;
      this.axios.all([
        this.axios.get(`${this.routes.getProjects}`),
        this.axios.get(`${this.routes.getArticles}`),
      ]).then(this.axios.spread((...responses) => {
        this.projects = responses[0].data.data;
        this.articles = responses[1].data.data;
        this.isFetched = true;
        this.isLoading = false;
      }));
    },

    selectProjectImages() {
      if (this.selectedProjectId == null) return;
      const index = this.projects.findIndex(x => x.id == this.selectedProjectId);
      this.selectedProject = this.projects[index];
    },

    selectedArticleImages() {
      if (this.selectedArticleId == null) return;
      const index = this.articles.findIndex(x => x.id == this.selectedArticleId);
      this.selectedArticle = this.articles[index];
    }
  }
}
</script>