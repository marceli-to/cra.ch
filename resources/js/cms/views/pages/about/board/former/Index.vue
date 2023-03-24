<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>


  <div v-for="(members, index) in data.members" :key="index">
    <h3 class="mb-3x">
      <strong v-if="index == 1">Geschäftsleitung</strong>
      <strong v-else-if="index == 2">Vorstand</strong>
      <strong v-else-if="index == 3">Sekretariat, Revisoren</strong>
    </h3>
    <draggable 
      :disabled="false"
      v-model="data.members[index]"
      @end="order(members)"
      ghost-class="draggable-ghost"
      draggable=".listing__item"
      class="listing">
      <div
        :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item is-draggable']"
        v-for="d in members"
        :key="d.id"
      >
        <div class="listing__item-body">
          {{d.description}}
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
              class="feather-icon"
              @click.prevent="destroy(d.id)"
            >
              <trash2-icon size="18"></trash2-icon>
            </a>
          </div>
        </div>
      </div>
    </draggable>
  </div>
  <former-board-member-form :type="'edit'" ref="formerBoardMemberForm" @formerMemberUpdated="update()"></former-board-member-form>
</div>
</template>
<script>
import { EyeIcon, EyeOffIcon, EditIcon, Trash2Icon } from 'vue-feather-icons';
import Helpers from "@/mixins/Helpers";
import Separator from "@/components/ui/Separator.vue";
import FormerBoardMemberForm from "@/views/pages/about/board/former/Form.vue";
import draggable from "vuedraggable";

export default {

  components: {
    EyeIcon,
    EyeOffIcon,
    EditIcon,
    Trash2Icon,
    Separator,
    FormerBoardMemberForm,
    draggable
  },

  props: {

    formerMembers: {
      type: [Object, Array],
      default: null,
    }
  },

  mixins: [Helpers],

  data() {
    return {

      data: {
        members: null,
      },

      // Routes
      routes: {
        delete: '/api/former-board-member',
        order: '/api/former-board-member/order',
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

  mounted() {
    this.data.members = this.$props.formerMembers;
  },

  methods: {

    edit(member) {
      this.$refs.formerBoardMemberForm.show(member);
    },

    update() {
      this.$emit('formerMemberUpdate');
    },

    destroy(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${id}`).then(response => {
          this.$emit('formerMemberDestroyed');
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