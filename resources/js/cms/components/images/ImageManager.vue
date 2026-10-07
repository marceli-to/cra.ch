<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <div class="form-row">
      <Uploader v-bind="imageUpload" @uploaded="store" />
    </div>
    <div class="form-row upload-listing" v-if="images.length">
      <a href="" class="icon-view" @click.prevent="view = view === 'grid' ? 'list' : 'grid'">
        <span v-if="view === 'grid'">Listen Ansicht</span>
        <span v-else>Grid Ansicht</span>
      </a>
      <div class="is-list" v-if="view === 'list'">
        <SortableList v-model="images" @end="order">
          <div v-for="image in images" :key="image.id" class="upload-item-row is-draggable">
            <figure>
              <img :src="imageUrl(image, 'thumbnail')" height="300" width="300">
            </figure>
            <div>
              <PhDotsSixVertical :size="18" weight="light" />
            </div>
          </div>
        </SortableList>
      </div>
      <div v-else>
        <figure v-for="image in images" :key="image.id" :class="[image.publish == 0 ? 'is-disabled' : '', 'upload-item']">
          <a :href="imageUrl(image)" target="_blank" class="upload__preview">
            <img :src="imageUrl(image, 'thumbnail')" height="300" width="300" loading="lazy">
          </a>
          <div class="upload__actions">
            <div>
              <div>
                <a href="javascript:;" class="feather-icon" :title="image.publish == 1 ? 'Verbergen' : 'Publizieren'" @click.prevent="toggle(image)">
                  <PhEye v-if="image.publish == 1" :size="18" weight="light" />
                  <PhEyeSlash v-else :size="18" weight="light" />
                </a>
              </div>
              <div>
                <a href="javascript:;" class="feather-icon" title="Bearbeiten" @click.prevent="editItem = image">
                  <PhPencilSimple :size="18" weight="light" />
                </a>
              </div>
              <div>
                <a href="javascript:;" class="feather-icon" title="Löschen" @click.prevent="destroy(image)">
                  <PhTrash :size="18" weight="light" />
                </a>
              </div>
              <div>
                <a href="javascript:;" class="feather-icon" title="Zuschneiden" @click.prevent="openCropper(image)">
                  <PhCrop :size="18" weight="light" />
                </a>
              </div>
            </div>
          </div>
        </figure>
      </div>
    </div>

    <Lightbox :open="!!editItem" title="Bild bearbeiten" @close="editItem = null">
      <div class="lightbox-grid" v-if="editItem">
        <figure>
          <img :src="imageUrl(editItem)" height="300" width="300">
          <figcaption v-if="editItem.caption">
            <span>{{ editItem.caption }}</span>
          </figcaption>
        </figure>
        <div>
          <div class="form-row">
            <label>Beschreibung</label>
            <textarea v-model="editItem.caption"></textarea>
          </div>
        </div>
      </div>
      <template #footer>
        <a href="javascript:;" class="btn-primary" @click.prevent="update(editItem); editItem = null">Speichern</a>
        <a href="javascript:;" @click.prevent="editItem = null">Abbrechen</a>
      </template>
    </Lightbox>

    <Lightbox :open="!!cropItem" title="Bild zuschneiden" fill @close="cropItem = null">
      <p class="lightbox__loading" v-if="isCropperLoading">Bild wird geladen...</p>
      <template v-else-if="cropItem">
        <div class="cropper-formats">
          <div v-for="format in formats" :key="format.label">
            <a
              href="javascript:;"
              :class="['btn-cropper-format', { 'is-active': cropRatio === format.ratio }]"
              @click.prevent="cropRatio = format.ratio"
            >{{ format.label }}</a>
          </div>
          <span class="cropper-info">{{ cropSize.w }} x {{ cropSize.h }}px</span>
        </div>
        <Cropper
          :key="cropRatio"
          :src="cropSrc"
          :default-position="defaultPosition"
          :default-size="defaultSize"
          :stencil-props="{
            aspectRatio: cropRatio,
            linesClassnames: { default: 'line' },
            handlersClassnames: { default: 'handler' },
          }"
          @change="change"
        />
      </template>
      <template #footer>
        <a href="javascript:;" class="btn-primary" @click.prevent="saveCrop()">Speichern</a>
        <a href="javascript:;" @click.prevent="cropItem = null">Abbrechen</a>
      </template>
    </Lightbox>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import { Cropper } from 'vue-advanced-cropper';
import { PhEye, PhEyeSlash, PhPencilSimple, PhTrash, PhCrop, PhDotsSixVertical } from '@phosphor-icons/vue';
import Lightbox from '@/components/ui/Lightbox.vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import SortableList from '@/components/ui/SortableList.vue';
import Uploader from '@/components/ui/Uploader.vue';
import { imageUrl, preloadImage } from '@/lib/images';
import { useImages, imageUpload } from '@/composables/useImages';
import { useOrder } from '@/composables/useOrder';

// Images of a form: upload, publish, caption, crop, order, delete. Every
// change is saved right away; new images are attached when the form is saved.
const props = defineProps({
  // crop formats { label, w, h }; the first is the default
  ratios: { type: Array, default: () => [{ label: 'Hoch', w: 3, h: 4 }, { label: 'Quer', w: 16, h: 10 }] },
});

// The record's images
const images = defineModel('images', { type: Array, required: true });

const isLoading = ref(false);
const { store, destroy, toggle, update, saveCoords } = useImages({ images, isLoading });
const order = useOrder({ url: '/api/images/order', key: 'images', delay: 1000 });

const view = ref('grid');

// Edit overlay
const editItem = ref(null);

// Cropper overlay
const formats = [
  ...props.ratios.map(({ label, w, h }) => ({ label: `${label} ${w} x ${h}`, ratio: w / h })),
  { label: 'Frei', ratio: null },
];
const cropItem = ref(null);
const isCropperLoading = ref(false);
const cropSrc = ref(null);
const cropRatio = ref(formats[0].ratio);
const coords = reactive({ w: 0, h: 0, x: 0, y: 0 });
const cropSize = reactive({ w: null, h: null });

// Where the crop box starts for an image without coords
const cropDefaults = { w: 425, h: 510, x: 0, y: 0 };

async function openCropper(image) {
  cropItem.value = image;
  cropRatio.value = formats[0].ratio;
  isCropperLoading.value = true;
  try {
    cropSrc.value = await preloadImage(imageUrl(image, 'original'));
  }
  finally {
    isCropperLoading.value = false;
  }
}

function change({ coordinates }) {
  coords.w = coordinates.width;
  coords.h = coordinates.height;
  coords.x = coordinates.left;
  coords.y = coordinates.top;
  cropSize.w = Math.floor(coordinates.width);
  cropSize.h = Math.floor(coordinates.height);
}

function defaultPosition() {
  return {
    left: cropItem.value.coords_x || cropDefaults.x,
    top: cropItem.value.coords_y || cropDefaults.y,
  };
}

function defaultSize() {
  return {
    width: cropItem.value.coords_w || cropDefaults.w,
    height: cropItem.value.coords_h || cropDefaults.h,
  };
}

function saveCrop() {
  const image = cropItem.value;
  image.coords_w = coords.w;
  image.coords_h = coords.h;
  image.coords_x = coords.x;
  image.coords_y = coords.y;
  saveCoords(image);
  cropItem.value = null;
}
</script>
