<template>
  <div>

    <!-- listing -->
    <div class="upload-listing">
      <a href="" class="icon-view" @click.prevent="toggleView()">
        <span v-if="view == 'grid'">Listen Ansicht</span>
        <span v-if="view == 'list'">Grid Ansicht</span>
      </a>
      <div class="is-list" v-if="view == 'list'">
        <template>
          <draggable 
            :disabled="false"
            v-model="imageData" 
            @end="order"
            ghost-class="draggable-ghost"
            draggable=".is-draggable">
            <div class="upload-item-row is-draggable" v-for="(image) in imageData" :key="image.id">
              <figure>
                <img :src="getSource(image, 'thumbnail')" height="300" width="300">
              </figure>
              <div>
                <span class="icon-move"></span>
              </div>
            </div>
          </draggable>
        </template>
      </div>
      <div v-if="view == 'grid'">
        <figure
          :class="[image.publish == 0 ? 'is-disabled' : '', 'upload-item']"
          v-for="image in images"
          :key="image.id"
        >
          <a :href="getSource(image, 'cache')" target="_blank" class="upload__preview">
            <img :src="getSource(image, 'thumbnail')" height="300" width="300">
          </a>
          <div class="upload__actions">
            <image-actions 
              :image="image" 
              :hasPreview="false"
              :publish="image.publish" 
              :imagePreviewRoute="'cache'">
            </image-actions>
          </div>
        </figure>
      </div>
    </div>
    <!-- // listing -->

    <!-- cropper -->
    <div :class="[hasOverlayCropper ? 'is-visible' : '', 'upload-overlay-cropper']">
      <div class="upload-overlay__loader" v-if="isLoading">Bild wird geladen...</div>
      <div class="upload-overlay__close" v-if="!isLoading">
        <a
          href="javascript:;"
          class="feather-icon icon-close-overlay"
          @click.prevent="hideCropper()"
        >
          <x-icon size="24"></x-icon>
        </a>
      </div>
      <div class="upload-overlay-cropper__wrapper" v-if="!isLoading">
        <div :class="'is-' + overlayItem.orientation">
          <div class="cropper-formats">
            <div>
              <a href="javascript:;" @click.prevent="changeRatio(2.8,4)" class="btn-cropper-format is-3-4">Hoch 3x4</a>
            </div>
            <div>
              <a href="javascript:;" @click.prevent="changeRatio(4,2.8)" class="btn-cropper-format is-4-3">Quer 4x3</a>
            </div>
            <div>
              <a href="javascript:;" @click.prevent="changeRatio(3,2)" class="btn-cropper-format is-4-3">Quer 3x2</a>
            </div>
          </div>
          <div class="cropper-info">{{ cropW }} x {{ cropH }}px</div>
          <cropper
            :src="cropImage"
            :defaultPosition="defaultPosition"
            :defaultSize="defaultSize"
            :stencilProps="{
              aspectRatio: this.ratio.w/this.ratio.h,
              linesClassnames: {
                default: 'line',
              },
              handlersClassnames: {
                default: 'handler'
              }
            }"
            @change="change"
          ></cropper>
          <div class="form-buttons flex justify-between">
            <a
              href="javascript:;"
              class="btn-secondary has-icon"
              @click.prevent="hideCropper()">
              <x-icon size="18"></x-icon>
              <span>Schliessen</span>
            </a>
            <a
              href="javascript:;"
              class="btn-primary has-icon"
              @click.prevent="saveCoords(overlayItem)">
              <download-icon size="18"></download-icon>
              <span>Speichern</span>
            </a>

          </div>
        </div>
      </div>
    </div>
    <!-- // cropper -->

    <!-- edit -->
    <div :class="[hasOverlayEdit ? 'is-visible' : '', 'upload-overlay-edit']">
      <div class="upload-overlay__close">
        <a
          href="javascript:;"
          class="feather-icon icon-close-overlay"
          @click.prevent="hideEdit()"
        >
          <x-icon size="24"></x-icon>
        </a>
      </div>
      <div class="upload-overlay__grid">
        <figure v-if="hasOverlayEdit">
            <img :src="getSource(overlayItem, 'cache')" height="300" width="300">
            <figcaption v-if="overlayItem.caption">
              <span v-if="overlayItem.caption">{{overlayItem.caption}}</span>
            </figcaption>
          </figure>
        <div>
          <div class="form-row">
            <label>Legende</label>
            <input type="text" v-model="overlayItem.caption" />
          </div>
          <div class="form-row">
            <label>Beschreibung</label>
            <textarea v-model="overlayItem.description"></textarea>
          </div>
          <div class="form-buttons flex justify-between">
            <a
              href="javascript:;"
              class="btn-secondary has-icon"
              @click.prevent="hideEdit()">
              <x-icon size="18"></x-icon>
              <span>Schliessen</span>
            </a>
            <a
              href="javascript:;"
              class="btn-primary has-icon"
              @click.prevent="update()">
              <download-icon size="18"></download-icon>
              <span>Speichern</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- // edit -->
  </div>
</template>
<script>

import draggable from 'vuedraggable';
import { Cropper } from "vue-advanced-cropper";
import { XIcon,DownloadIcon } from 'vue-feather-icons';
import ImageActions from "@/modules/images/components/Actions.vue";
import ImageEdit from "@/modules/images/mixins/edit";
import ImageCrop from "@/modules/images/mixins/crop";
import ImageUtils from "@/modules/images/mixins/utils";

export default {
  
  components: {
    ImageActions,
    XIcon,
    DownloadIcon,
    Cropper,
    draggable
  },

  mixins: [ImageUtils, ImageEdit, ImageCrop],

  props: {

    images: {
      type: Array,
      default: [],
    },

    imagePreviewRoute: {
      type: String,
      default: "cache"
    },

    ratioW: {
      type: Number,
      default: 3
    },

    ratioH: {
      type: Number,
      default: 2
    },

    allowRatioSwitch: {
      type: Boolean,
      default: true,
    }
  },

  data() {
    return {

      // Data
      imageData: null,
      view: 'grid',
      ratio: {
        w: null,
        h: null
      },
      
      // States
      isFetched: false,
      isLoading: false,

      // Routes
      routes: {
        update: '/api/image',
      },

      // Messages
      messages: {
        updated: 'Daten aktualisiert!',
      },
    };
  },

  mounted() {
    this.imageData = this.$props.images;
    this.ratio.w = this.$props.ratioW;
    this.ratio.h = this.$props.ratioH;
  },

  methods: {

    changeRatio(w,h) {
      this.ratio.w = w;
      this.ratio.h = h;
      this.resetCropper();
    },

    resetCropper() {
      let image = this.overlayItem;
      this.hideCropper();
      this.showCropper(image, true)
    },


    update() {
      this.axios.put(`${this.routes.update}/${this.overlayItem.id}`, this.overlayItem).then((response) => {
        this.$notify({type: 'success', text: this.messages.updated});
        this.hideEdit();
      });
    },

    order() {
      let images = this.imageData.map(function(image, index) {
        image.order = index;
        return image;
      });

      if (this.debounce) return;

      this.debounce = setTimeout(function(images) {
        this.debounce = false;
        let uri = `/api/images/order`;
        this.axios.post(uri, {images: images}).then((response) => {
          this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
        });
      }.bind(this, images), 1000);
    },

    toggleView() {
      this.imageData = this.$props.images;
      this.view = this.view == 'grid' ? 'list' : 'grid';
    }
  }

};
</script>