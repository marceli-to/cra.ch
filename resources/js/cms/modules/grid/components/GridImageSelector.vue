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
            <select v-model="selectedProjectId" @change="selectImages()">
              <option :value="null">Bitte wählen...</option>
              <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option>
            </select>
          </div>
          <div class="grid-asset-selector__images mt-2x" v-if="selectedProjectId && selectedProject.images.length">
            <figure v-for="image in selectedProject.images" :key="image.id">
              <img 
                :src="getSource(image, 'cache')" 
                height="300" 
                width="300" v-if="image"
                @click="$emit('select', {image: image.id, project: selectedProject.id})" />
            </figure>
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
      selectedProjectId: null,
      selectedProject: [],

      isFetched: false,
      isLoading: false,

      routes: {
        getProject: '/api/project',
        getDiary: '/api/diary',
        getProjects: '/api/projects',
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
      this.fetchProjects();
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

    fetchProjects() {
      this.isLoading = true;
      this.axios.get(`${this.routes.getProjects}`).then(response => {
        this.projects = response.data.data;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    selectImages() {
      if (this.selectedProjectId == null) return;
      const index = this.projects.findIndex(x => x.id == this.selectedProjectId);
      this.selectedProject = this.projects[index];
    },
  }
}
</script>