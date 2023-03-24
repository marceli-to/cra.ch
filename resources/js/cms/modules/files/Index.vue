<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <div class="form-row">
      <file-upload
        :restrictions="'pdf, zip, txt, doc | max. 32 MB'"
        :maxFiles="99"
        :maxFilesize="32"
        :acceptedFiles="'.pdf,.zip,.txt,.doc'"
      ></file-upload>
    </div>
    <div class="form-row">
      <file-edit :files="data"></file-edit>
    </div>
  </div>
</div>
</template>
<script>
import Helpers from "@/mixins/Helpers";
import FileUpload from "@/modules/files/components/Upload.vue";
import FileEdit from "@/modules/files/components/Edit.vue";

export default {

  components: {
    FileUpload,
    FileEdit
  },

  mixins: [Helpers],

  props: {
    typeId: null,
    type: null,
    files: null,
  },

  data() {
    return {

      // Data
      data: null,

      // Routes
      routes: {
        get: '/api/files',
        store: '/api/file',
        delete: '/api/file',
        toggle: '/api/file/state',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Dateien vorhanden...',
        saved: 'Datei gespeichert!',
        updated: 'Änderungen gespeichert!',
      },
    };
  },

  created() {
    if (this.$props.files) {
      this.data = this.$props.files;
      this.isFetched = true;
    }
    else {
      this.fetch();
    }
  },

  methods: {

    fetch() {
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
      });
    },

    store(media) {
      let file = {
        id: null,
        name: media.name,
        original_name: media.original_name,
        size: media.size,
        extension: media.extension,
        publish: 1,
        fileable_id: this.$props.typeId,
        fileable_type: this.$props.type,
      };

      this.axios.post(`${this.routes.store}`, file).then(response => {
        this.$notify({ type: "success", text: this.messages.saved });
        file.id = response.data.fileId;
        this.data.push(file);
      });
    },

    destroyFile(file) {
      if (confirm("Bitte löschen bestätigen!")) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${file}`).then(response => {
          const index = this.data.findIndex(x => x.name === file);
          this.data.splice(index, 1);
          this.isLoading = false;
        });
      }
    },

    toggleFile(file) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${file.id}`).then(response => {
        const index = this.data.findIndex(x => x.id === file.id);
        this.data[index].publish = response.data;
        this.isLoading = false;
      });
    },
  }
}
</script>