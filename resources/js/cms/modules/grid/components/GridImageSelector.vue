<template>
  <div>
    <loading-indicator v-if="isLoading"></loading-indicator>
    <div class="grid-asset-selector" v-if="isFetched">
      <div>
        <a href="" class="btn-close" @click.prevent="$emit('close')">
          <x-icon size="24"></x-icon>
        </a>
        <template v-if="isProject">
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
        <template v-if="isDiary">
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

        <template v-if="isContent">
          <div>
            <h2 class="mb-4x">Bildtyp wählen:</h2>
            <a href="javascript:;" class="btn-secondary mr-2x" @click="setType('projects')" v-if="projects.length">Projekt</a>
            <a href="javascript:;" class="btn-secondary mr-2x" @click="setType('diary')" v-if="diary.images.length">Tagebuch</a>
            <a href="javascript:;" class="btn-secondary" @click="setType('content')" v-if="serviceImages.length || aboutImages.length">Inhalte</a>
          </div>
          <template v-if="projects.length && imageType == 'projects'">
            <h2 class="mt-4x">Projekt wählen:</h2>
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
          <template v-if="articles.length && imageType == 'articles'">
            <h2 class="mt-4x">Artikel wählen:</h2>
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
                    @click="$emit('select', {image: image.id, article: selectedArticle.id})" />
                </figure>
              </template>
              <template v-else>
                Es sind keine Bilder für diesen Artikel vorhanden.
              </template>
            </div>        
          </template>
          <template v-if="(serviceImages.length || aboutImages.length) && imageType == 'content'">
            <h2 class="mt-6x mb-2x">Leistungen</h2>
            <div class="grid-asset-selector__images" style="margin-top:0;margin-left: -4px;">
              <template v-if="serviceImages.length">
                <figure v-for="image in serviceImages" :key="image.id">
                  <img 
                    :src="getSource(image, 'cache')" 
                    height="300" 
                    width="300" v-if="image"
                    @click="$emit('select', {image: image.id, page: 'service'})" />
                </figure>
              </template>
            </div>
            <h2 class="mt-4x mb-2x">Team</h2>
            <div class="grid-asset-selector__images" style="margin-top:0;margin-left: -4px;">
              <template v-if="aboutImages.length">
                <figure v-for="image in aboutImages" :key="image.id">
                  <img 
                    :src="getSource(image, 'cache')" 
                    height="300" 
                    width="300" v-if="image"
                    @click="$emit('select', {image: image.id, page: 'about.team'})" />
                </figure>
              </template>
              <template v-else>
                Es sind keine Bilder für diesen Artikel vorhanden.
              </template>
            </div>        
          </template>
          <template v-if="diary.images.length && imageType == 'diary'">
            <h2 class="mt-6x mb-2x">Tagebuch</h2>
            <div class="grid-asset-selector__images" style="margin-top:0;margin-left: -4px;">
            <figure v-for="image in diary.images" :key="image.id">
              <img 
                :src="getSource(image, 'cache')" 
                height="300" 
                width="300" v-if="image"
                @click="$emit('select', {image: image.id, page: 'about.diary'})" />
            </figure>
          </div>  
          </template>
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
      serviceImages: [],
      aboutImages: [],
      selectedProjectId: null,
      selectedArticleId: null,
      selectedProject: [],
      selectedArticle: [],

      imageType: null,

      isProject: false,
      isDiary: false,
      isContent: false,
      isFetched: false,
      isLoading: false,

      routes: {
        getProject: '/api/project',
        getDiary: '/api/diary',
        getProjects: '/api/projects/1',
        getArticles: '/api/articles/1',
        getServiceImages: '/api/service/images',
        getAboutImages: '/api/about/images',
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
      this.fetchProjectsAndArticlesAndContent();
    }
  },

  methods: {

    fetchProject(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.getProject}/${id}`).then(response => {
        this.project = response.data.project;
        this.isProject = true;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchDiary(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.getDiary}/${id}`).then(response => {
        this.diary = response.data.diary;
        this.isDiary = true;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchProjectsAndArticlesAndContent() {
      this.isLoading = true;
      this.axios.all([
        this.axios.get(`${this.routes.getProjects}`),
        this.axios.get(`${this.routes.getArticles}`),
        this.axios.get(`${this.routes.getServiceImages}`),
        this.axios.get(`${this.routes.getAboutImages}`),
        this.axios.get(`${this.routes.getDiary}/1`)
      ]).then(this.axios.spread((...responses) => {
        this.projects = responses[0].data.data;
        this.articles = responses[1].data.data;
        this.serviceImages = responses[2].data.data;
        this.aboutImages = responses[3].data.data;
        this.diary = responses[4].data.diary;
        this.isContent = true;
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
    },

    setType(type) {
      this.imageType = type;
    }
  }
}
</script>