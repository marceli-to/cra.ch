<template>
  <div class="listing__item-action">

    <div v-if="hasDownload">
      <a
        :href="'/storage/uploads/' + record.file"
        target="_blank"
        class="feather-icon"
      >
        <download-cloud-icon size="18"></download-cloud-icon>
      </a>
    </div>

    <div v-if="hasEdit">
      <router-link
        :to="{name: routes.edit, params: { id: id }}"
        class="feather-icon"
      >
        <edit-icon size="18"></edit-icon>
      </router-link>
    </div>

    <div v-if="hasToggle">
      <a
        href="javascript:;"
        @click.prevent="toggle(id,$event)"
      >
        <span v-if="record.publish" class="feather-icon">
          <eye-icon size="18"></eye-icon>
        </span>
        <span v-else>
          <eye-off-icon size="18" class="feather-icon"></eye-off-icon>
        </span>
      </a>
    </div>

    <div v-if="hasCopy">
      <a
        href="javascript:;"
        class="feather-icon"
        @click.prevent="copy(id,$event)"
      >
        <copy-icon size="18"></copy-icon>
      </a>
    </div>

    <div v-if="hasDestroy">
      <a
        href="javascript:;"
        class="feather-icon"
        @click.prevent="destroy(id,$event)"
      >
        <trash2-icon size="18"></trash2-icon>
      </a>
    </div>

  </div>
</template>
<script>

import { 
  EyeIcon,
  EyeOffIcon,
  EditIcon,
  Trash2Icon,
  CopyIcon,
  DownloadCloudIcon,
  SettingsIcon,
  LayersIcon,
  UserIcon,
  UsersIcon,
  DollarSignIcon,
  ClipboardIcon,
  MailIcon,
  XCircleIcon
}
from 'vue-feather-icons';

export default {

  components: {
    EyeIcon,
    EyeOffIcon,
    EditIcon,
    Trash2Icon,
    CopyIcon,
    DownloadCloudIcon,
    SettingsIcon,
    LayersIcon,
    UserIcon,
    UsersIcon,
    DollarSignIcon,
    ClipboardIcon,
    MailIcon,
    XCircleIcon
  },

  props: {

    id: {
      type: Number,
      default: null
    },

    hasEdit: {
      type: Boolean,
      default: true
    },
    
    hasDestroy: {
      type: Boolean,
      default: true
    },
    
    hasToggle: {
      type: Boolean,
      default: true
    },

    hasCopy: {
      type: Boolean,
      default: false
    },

    hasDownload: {
      type: Boolean,
      default: false,
    },

    hasDraggable: {
      type: Boolean,
      default: false
    },

    isCollapsible: {
      type: Boolean,
      default: false,
    },

    model: {
      type: String,
      default: null,
    },
    routes: Object,
    record: Object,
  },

  methods: {

    toggle(id,$event) {
      if (this.hasDraggable || this.isCollapsible) {
        this.$parent.$parent.toggle(id,$event,this.model);
      }
      else {
        this.$parent.toggle(id,$event,this.model);
      }
    },

    destroy(id,$event) {
      if (this.hasDraggable || this.isCollapsible) {
        this.$parent.$parent.destroy(id,$event,this.model);
      }
      else {
        this.$parent.destroy(id,$event,this.model);
      }
    },

    copy(id,$event) {
      this.$parent.copy(id,$event,this.model);
    },

  },
}
</script>

