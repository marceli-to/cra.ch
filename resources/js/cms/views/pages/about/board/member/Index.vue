<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <draggable 
    :disabled="false"
    v-model="data.members" 
    @end="order(data.members)"
    ghost-class="draggable-ghost"
    draggable=".listing__item"
    class="listing"
    v-if="data.members.length">
    <div
      :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item is-draggable']"
      v-for="d in data.members"
      :key="d.id"
    >
      <div class="listing__item-body">
        {{d.name}}
      </div>
      <div class="listing__item-action">
        <div>
          <a href="javascript:;" @click="edit(d)">
            <edit-icon size="18"></edit-icon>
          </a>
        </div>
        <div>
          <a
            href="javascript:;"
            @click.prevent="toggle(d.id)"
          >
            <span v-if="d.publish" class="feather-icon">
              <eye-icon size="18"></eye-icon>
            </span>
            <span v-else>
              <eye-off-icon size="18" class="feather-icon"></eye-off-icon>
            </span>
          </a>
        </div>
        <div>
          <a
            href="javascript:;"
            class="feather-icon"
            @click.prevent="destroy(d.id)"
          >
            <trash2-icon size="18"></trash2-icon>
          </a>
        </div>
      </div>
    </div>
  </draggable>
  <board-member-form :type="'edit'" ref="boardMemberForm"></board-member-form>
</div>
</template>
<script>
import { EyeIcon, EyeOffIcon, EditIcon, Trash2Icon } from 'vue-feather-icons';
import Helpers from "@/mixins/Helpers";
import Separator from "@/components/ui/Separator.vue";
import BoardMemberForm from "@/views/pages/about/board/member/Form.vue";
import draggable from "vuedraggable";

export default {

  components: {
    EyeIcon,
    EyeOffIcon,
    EditIcon,
    Trash2Icon,
    Separator,
    BoardMemberForm,
    draggable
  },

  props: {
    forumId: null,

    members: {
      type: Array,
      default: null,
    }
  },

  mixins: [Helpers],

  data() {
    return {

      data: {
        articleId: null,
        members: [],
      },

      // Routes
      routes: {
        toggle: '/api/board-member/state',
        delete: '/api/board-member',
        order: '/api/board-member/order',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        confirm: 'Bitte löschen bestätigen!',
        updated: 'Daten aktualisiert',
        deleted: 'Artikel gelöscht',
      }
    };
  },

  created() {
    this.data.members = this.$props.members;
  },

  methods: {

    edit(member) {
      this.$refs.boardMemberForm.show(member);
    },

    toggle(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${id}`).then(response => {
        const index = this.data.members.findIndex(x => x.id === id);
        this.data.members[index].publish = response.data;
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },

    destroy(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${id}`).then(response => {
          const idx = this.data.members.findIndex(x => x.id === id);
          if (idx > -1) {
            this.data.members.splice(idx, 1);
          }
          this.$notify({ type: "success", text: this.messages.deleted });
          this.isLoading = false;
        });
      }
    },

    order(data) {
      let members = data.map(function(member, index) {
        member.order = index;
        return member;
      });
      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false;
        this.axios.post(`${this.routes.order}`, {items: members}).then((response) => {
          this.$notify({type: 'success', text: this.messages.updated});
        });
      }.bind(this, members), 500);
    },
  }
}
</script>