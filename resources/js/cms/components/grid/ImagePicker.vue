<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="owner.name !== 'Home'">
      <div class="grid-picker__images" v-if="owner.model.images.length">
        <img
          v-for="image in owner.model.images"
          :key="image.id"
          :src="imageUrl(image, 'thumbnail')"
          height="300"
          width="300"
          @click="emit('select', { image_id: image.id, project_id: owner.name === 'Project' ? owner.model.id : null })"
        >
      </div>
      <p v-else>Es sind noch keine Bilder vorhanden.</p>
    </template>
    <template v-else-if="isFetched">
      <div class="grid-picker__types">
        <a href="javascript:;" :class="['btn-secondary', { 'is-active': type === 'projects' }]" @click="type = 'projects'" v-if="projects.length">Projekt</a>
        <a href="javascript:;" :class="['btn-secondary', { 'is-active': type === 'diary' }]" @click="type = 'diary'" v-if="diaryImages.length">Tagebuch</a>
        <a href="javascript:;" :class="['btn-secondary', { 'is-active': type === 'content' }]" @click="type = 'content'" v-if="pages.some(page => page.images.length)">Inhalte</a>
      </div>
      <template v-if="type === 'projects'">
        <div class="form-row">
          <label>Projekt</label>
          <div class="select-wrapper">
            <select v-model="projectId">
              <option :value="null">Bitte wählen...</option>
              <option v-for="project in projects" :key="project.id" :value="project.id">{{ project.title }}</option>
            </select>
          </div>
        </div>
        <template v-if="project">
          <div class="grid-picker__images" v-if="project.images.length">
            <img
              v-for="image in project.images"
              :key="image.id"
              :src="imageUrl(image, 'thumbnail')"
              height="300"
              width="300"
              @click="emit('select', { image_id: image.id, project_id: project.id })"
            >
          </div>
          <p v-else>Es sind keine Bilder für dieses Projekt vorhanden.</p>
        </template>
      </template>
      <div class="grid-picker__images" v-if="type === 'diary'">
        <img
          v-for="image in diaryImages"
          :key="image.id"
          :src="imageUrl(image, 'thumbnail')"
          height="300"
          width="300"
          @click="emit('select', { image_id: image.id, page: 'about.diary' })"
        >
      </div>
      <template v-if="type === 'content'">
        <template v-for="page in pages" :key="page.page">
          <h2>{{ page.label }}</h2>
          <div class="grid-picker__images">
            <img
              v-for="image in page.images"
              :key="image.id"
              :src="imageUrl(image, 'thumbnail')"
              height="300"
              width="300"
              @click="emit('select', { image_id: image.id, page: page.page })"
            >
          </div>
        </template>
      </template>
    </template>
  </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import http from '@/lib/http';
import { imageUrl } from '@/lib/images';

// Picks the image for a grid slot. Projects and diaries pick from their own
// images; the home from published projects, the diary and the pages.
const props = defineProps({
  // { name: 'Home' | 'Diary' | 'Project', model }
  owner: { type: Object, required: true },
});

// ({ image_id, project_id?, page? })
const emit = defineEmits(['select']);

const isLoading = ref(false);
const isFetched = ref(false);
const type = ref(null);
const projects = ref([]);
const projectId = ref(null);
const project = computed(() => projects.value.find(project => project.id === projectId.value));
const diaryImages = ref([]);
const pages = ref([
  { page: 'service', label: 'Leistungen', url: '/api/service/images', images: [] },
  { page: 'about.team', label: 'Team', url: '/api/about/images', images: [] },
  { page: 'contact', label: 'Kontakt', url: '/api/contact/images', images: [] },
]);

async function fetch() {
  isLoading.value = true;
  try {
    const [projectList, diary, ...pageImages] = await Promise.all([
      http.get('/api/projects/1'),
      http.get('/api/diary/1'),
      ...pages.value.map(page => http.get(page.url)),
    ]);
    projects.value = projectList.data.data;
    diaryImages.value = diary.data.diary.images;
    pageImages.forEach((response, index) => pages.value[index].images = response.data.data);
    isFetched.value = true;
  }
  catch {
    // Notified by the http error handler
  }
  finally {
    isLoading.value = false;
  }
}

if (props.owner.name === 'Home') {
  fetch();
}
</script>
