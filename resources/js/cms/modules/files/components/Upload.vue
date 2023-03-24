<template>
  <div>
    <vue-dropzone-file
      ref="dropzone"
      id="dropzone"
      :options="dropzoneConfig"
      :class="$props.buttonClass"
      @vdropzone-complete="complete"
    ></vue-dropzone-file>
    <span class="bubble is-restriction">{{restrictions}}</span>
  </div>
</template>
<script>
import vue2Dropzone from "vue2-dropzone";
import dropzoneConfig from "@/modules/files/config/upload.js";

export default {

  components: {
    vueDropzoneFile: vue2Dropzone,
  },

  props: {
    restrictions: String,
    acceptedFiles: String,
    maxFiles: Number,
    maxFilesize: Number,
    buttonClass: String,
  },

  data() {
    return {
      dropzoneConfig: dropzoneConfig,
      messages: {
        uploadError: 'Invalid format or file to big!'
      }
    };
  },

  created() {
    this.dropzoneConfig.acceptedFiles = this.$props.acceptedFiles;
    this.dropzoneConfig.maxFiles = this.$props.maxFiles;
    this.dropzoneConfig.maxFilesize = this.$props.maxFilesize;
  },

  methods: {

    complete(file) {
      if (file.status == "error" && file.accepted == false) {
        this.$notify({ type: "error", text: this.messages.uploadError });
      } 
      else {
        let response = JSON.parse(file.xhr.response);
        this.$parent.store(response);
      }
      this.$refs.dropzone.removeFile(file);
    },
  }
};
</script>