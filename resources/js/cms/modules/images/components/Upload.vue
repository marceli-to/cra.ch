<template>
  <div>
    <vue-dropzone-image
      ref="dropzoneImage"
      id="dropzoneImage"
      :options="dropzoneConfig"
      @vdropzone-complete="complete"
    ></vue-dropzone-image>
    <span class="bubble is-restriction">{{restrictions}}</span>
  </div>
</template>
<script>
import vue2Dropzone from "vue2-dropzone";
import dropzoneConfig from "@/modules/images/config/upload.js";

export default {

  components: {
    vueDropzoneImage: vue2Dropzone,
  },

  props: {
    restrictions: String,
    acceptedFiles: String,
    maxFiles: Number,
    maxFilesize: Number,
  },

  data() {
    return {
      dropzoneConfig: dropzoneConfig,
      messages: {
        uploadError: 'Ungültiges Format oder Datei zu gross.'
      }
    };
  },

  created() {
    this.dropzoneConfig.acceptedFiles = this.$props.acceptedFiles;
    this.dropzoneConfig.maxFiles = this.$props.maxFiles;
    this.dropzoneConfig.maxFilesize = this.$props.maxFilesize;
  },

  methods: {

    complete(image) {
      if (image.status == "error") {
        let message = this.messages.uploadError;
        try {
          message = JSON.parse(image.xhr.response).error || message;
        } 
        catch (e) {}
        this.$notify({ type: "error", text: message, duration: 8000 });
      } 
      else {
        let response = JSON.parse(image.xhr.response);
        this.$parent.store(response);
      }
      this.$refs.dropzoneImage.removeFile(image);
    },
  }
};
</script>