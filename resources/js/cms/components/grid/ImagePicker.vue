<template>
  <div class="grid-picker-root">
    <LoadingIndicator v-if="isLoading" />
    <template v-if="owner.name !== 'Home'">
      <ul role="list" class="grid-picker__images" v-if="owner.model.images.length">
        <li v-for="image in owner.model.images" :key="image.id">
          <button type="button" class="grid-picker__image" @click="emit('select', { image_id: image.id, project_id: owner.name === 'Project' ? owner.model.id : null })">
            <img :src="imageUrl(image, 'small')" height="300" width="300" loading="lazy" alt="">
            <span>{{ image.caption || image.original_name }}</span>
          </button>
        </li>
      </ul>
      <p class="grid-picker__empty" v-else>Es sind noch keine Bilder vorhanden.</p>
    </template>
    <div class="grid-picker" v-else-if="isFetched">
      <nav class="grid-picker__sources" aria-label="Bilder von">
        <section v-for="group in groups" :key="group.label">
          <h3 class="grid-picker__label">{{ group.label }}</h3>
          <ul role="list">
            <li v-for="source in group.sources" :key="source.key">
              <button
                type="button"
                :class="{ 'is-active': source === current }"
                :aria-current="source === current ? 'true' : undefined"
                @click="current = source"
              >{{ source.label }}</button>
            </li>
          </ul>
        </section>
      </nav>
      <div v-if="current">
        <h3 class="grid-picker__title">{{ current.label }}</h3>
        <ul role="list" class="grid-picker__images">
          <li v-for="image in current.images" :key="image.id">
            <button type="button" class="grid-picker__image" @click="emit('select', { image_id: image.id, ...current.selection })">
              <img :src="imageUrl(image, 'small')" height="300" width="300" loading="lazy" alt="">
              <span>{{ image.caption || image.original_name }}</span>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import http from '@/lib/http';
import { imageUrl } from '@/lib/images';

// Picks the image for a grid slot. Projects and diaries pick from their own
// images; the home from published projects and the pages, listed on the
// left (the first project is shown at once). The Lightbox body scrolls, the
// list stays in place.
const props = defineProps({
  // { name: 'Home' | 'Diary' | 'Project', model }
  owner: { type: Object, required: true },
});

// ({ image_id, project_id?, page? })
const emit = defineEmits(['select']);

const isLoading = ref(false);
const isFetched = ref(false);
const projects = ref([]);
const pages = ref([]);
const current = ref(null);

// Sources without images are left out
const groups = computed(() => [
  { label: 'Projekte', sources: projects.value },
  { label: 'Seiten', sources: pages.value },
].map(group => ({ ...group, sources: group.sources.filter(source => source.images.length) }))
  .filter(group => group.sources.length));

async function fetch() {
  isLoading.value = true;
  try {
    const [projectList, diary, service, about, contact] = await Promise.all([
      http.get('/api/projects/1'),
      http.get('/api/diary/1'),
      http.get('/api/service/images'),
      http.get('/api/about/images'),
      http.get('/api/contact/images'),
    ]);
    projects.value = projectList.data.data.map(project => ({
      key: `project-${project.id}`,
      label: project.title,
      images: project.images,
      selection: { project_id: project.id },
    }));
    pages.value = [
      { key: 'diary', label: 'Tagebuch', images: diary.data.diary.images, selection: { page: 'about.diary' } },
      { key: 'service', label: 'Leistungen', images: service.data.data, selection: { page: 'service' } },
      { key: 'team', label: 'Team', images: about.data.data, selection: { page: 'about.team' } },
      { key: 'contact', label: 'Kontakt', images: contact.data.data, selection: { page: 'contact' } },
    ];
    current.value = groups.value[0]?.sources[0] ?? null;
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
