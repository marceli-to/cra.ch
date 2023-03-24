<template>
  <div>
    <div v-if="$props.hasDownload">
      <a
        :href="`/storage/uploads/${file.name}`"
        class="feather-icon"
        target="_blank">
        <download-cloud-icon size="18"></download-cloud-icon>
      </a>
    </div>
    <div v-if="$props.hasEdit">
      <a
        href="javascript:;"
        class="feather-icon"
        @click.prevent="showEdit(file)">
        <edit-icon size="18"></edit-icon>
      </a>
    </div>
    <div v-if="$props.hasDestroy">
      <a
        href="javascript:;"
        class="feather-icon"
        @click.prevent="destroy(file,$event)">
        <trash-2-icon size="18"></trash-2-icon>
      </a>
    </div>
  </div>
</template>
<script>
import { EditIcon, Trash2Icon, DownloadCloudIcon} from 'vue-feather-icons';

export default {

  components: {
    EditIcon,
    Trash2Icon,
    DownloadCloudIcon
  },

  props: {
    file: Object,
    publish: Number,

    hasDownload: {
      type: Boolean,
      default: false
    },

    hasDestroy: {
      type: Boolean,
      default: true
    },

    hasEdit: {
      type: Boolean,
      default: true
    },
  },

  methods: {

    toggle(file, $event) {
      this.$parent.toggle(file,$event);
    },

    destroy(file, $event) {
      this.$parent.destroy(file.name,$event);
    },

    showEdit(file) {
      this.$parent.showEdit(file);
    },
  }
}
</script>
