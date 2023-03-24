<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <draggable 
    :disabled="false"
    v-model="data.articles" 
    @end="order(data.articles)"
    ghost-class="draggable-ghost"
    draggable=".listing__item"
    class="listing"
    v-if="data.articles.length">
    <div
      :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item is-draggable']"
      v-for="d in data.articles"
      :key="d.id"
    >
      <div class="listing__item-body">
        {{d.title}}
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
  <forum-article-form :type="'edit'" ref="forumArticleForm"></forum-article-form>
</div>
</template>
<script>
import { EyeIcon, EyeOffIcon, EditIcon, Trash2Icon } from 'vue-feather-icons';
import Helpers from "@/mixins/Helpers";
import Separator from "@/components/ui/Separator.vue";
import ForumArticleForm from "@/views/pages/about/forum/article/Form.vue";
import draggable from "vuedraggable";

export default {

  components: {
    EyeIcon,
    EyeOffIcon,
    EditIcon,
    Trash2Icon,
    Separator,
    ForumArticleForm,
    draggable
  },

  props: {
    forumId: null,

    articles: {
      type: Array,
      default: null,
    }
  },

  mixins: [Helpers],

  data() {
    return {

      data: {
        articleId: null,
        articles: [],
      },

      // Routes
      routes: {
        toggle: '/api/forum-article/state',
        delete: '/api/forum-article',
        order: '/api/forum-article/order',
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
    this.data.articles = this.$props.articles;
  },

  methods: {

    edit(article) {
      this.$refs.forumArticleForm.show(article);
    },

    toggle(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${id}`).then(response => {
        const index = this.data.articles.findIndex(x => x.id === id);
        this.data.articles[index].publish = response.data;
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },

    destroy(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${id}`).then(response => {
          const idx = this.data.articles.findIndex(x => x.id === id);
          if (idx > -1) {
            this.data.articles.splice(idx, 1);
          }
          this.$notify({ type: "success", text: this.messages.deleted });
          this.isLoading = false;
        });
      }
    },

    order(data) {
      let articles = data.map(function(article, index) {
        article.order = index;
        return article;
      });
      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false;
        this.axios.post(`${this.routes.order}`, {items: articles}).then((response) => {
          this.$notify({type: 'success', text: this.messages.updated});
        });
      }.bind(this, articles), 500);
    },
  }
}
</script>