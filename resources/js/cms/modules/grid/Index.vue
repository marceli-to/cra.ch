<template>
<div v-if="isFetched">

  <page-header>
    <h1 v-if="$props.modelName == 'Project'">{{ $props.model.title.de }}</h1>
    <h1 v-else>Startseite</h1>
    <div>
      <a href="" class="btn-add has-icon mr-2x" @click.prevent="toggleGridSelector()">
        <plus-icon size="16"></plus-icon>
        <span>Hinzufügen</span>
      </a>
      <a href="" class="btn-move has-icon" @click.prevent="toggleSortable()">
        <move-icon size="16"></move-icon>
        <span>Verschieben</span>
      </a>
    </div>
  </page-header>

  <grid-layout-selector 
    :modelName="$props.modelName"
    @select="addRow($event)" 
    v-if="hasGridSelector">
  </grid-layout-selector>

  <grid-image-selector
    :projectId="$props.modelName == 'Project' ? $props.model.id : null"
    @close="toggleImageSelector()"
    @select="addImage($event)"
    v-if="hasImageSelector">
  </grid-image-selector>  

  <grid-article-selector
    :projectId="null"
    @close="toggleArticleSelector()"
    @select="addArticle($event)"
    v-if="hasArticleSelector">
  </grid-article-selector>  

  <template v-if="!isSortable">
    <grid-row 
      :key="grid.id" 
      v-for="grid in $props.grids" 
      :class="`grid-layout grid-layout--${grid.layout}`">
      <a 
        href="javascript:;" 
        class="btn-delete has-icon"
        @click.prevent="deleteRow(grid.id)">
        <trash-2-icon size="16"></trash-2-icon>
        <span>Zeile löschen</span>
      </a>

      <div>

        <template v-if="grid.layout == '1'">
          <grid-row-item
            v-for="item in grid.grid_items"
            :key="item.id"
            :item="item"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-row-item>  
        </template>

        <template v-if="grid.layout == '1:f'">
          <grid-row-item
            v-for="item in grid.grid_items"
            :key="item.id"
            :item="item"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-row-item>  
        </template>


        <template v-if="grid.layout == '1:w'">
          <grid-row-item
            v-for="item in grid.grid_items"
            :key="item.id"
            :item="item"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-row-item>  
        </template>

        <template v-if="grid.layout == '1:1'">
          <grid-row-item
            v-for="item in grid.grid_items"
            :key="item.id"
            :item="item"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-row-item>  
        </template>

        <template v-if="grid.layout == '1:1:f'">
          <grid-row-item
            v-for="item in grid.grid_items"
            :key="item.id"
            :item="item"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-row-item>  
        </template>

        <template v-if="grid.layout == '1:2'">
          <grid-row-item 
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-row-item>
          <div class="grid-stack">
            <grid-row-item 
              :item="grid.grid_items[1] ? grid.grid_items[1] : null"
              :hasArticles="hasArticles"
              @resetItem="resetItem($event)"
              @showImages="showImageSelector($event)"
              @showArticles="showArticleSelector($event)">
            </grid-row-item>
            <grid-row-item 
              :item="grid.grid_items[2] ? grid.grid_items[2] : null"
              @resetItem="resetItem($event)"
              @showImages="showImageSelector($event)"
              @showArticles="showArticleSelector($event)">
            </grid-row-item>
          </div>
        </template>

        <template v-if="grid.layout == '2:1'">
          <div class="grid-stack">
            <grid-row-item 
              :item="grid.grid_items[0] ? grid.grid_items[0] : null"
              :hasArticles="hasArticles"
              @resetItem="resetItem($event)"
              @showImages="showImageSelector($event)"
              @showArticles="showArticleSelector($event)">
            </grid-row-item>
            <grid-row-item 
              :item="grid.grid_items[1] ? grid.grid_items[1] : null"
              :hasArticles="hasArticles"
              @resetItem="resetItem($event)"
              @showImages="showImageSelector($event)"
              @showArticles="showArticleSelector($event)">
            </grid-row-item>
          </div>
          <grid-row-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-row-item>
        </template>

      </div>

    </grid-row>
  </template>

  <template v-else>
    <draggable 
      :disabled="false"
      v-model="gridItems" 
      @end="order()"
      ghost-class="draggable-ghost"
      draggable=".is-draggable"
      class="listing"
      v-if="gridItems.length">
      <div v-for="grid in gridItems" :key="grid.id" class="listing__item is-draggable">
        <grid-layout :layout="grid.layout" :items="grid.grid_items" :isOrderView="true" />
      </div> 
    </draggable>
  </template>
</div>
</template>
<script>
import ErrorHandling from "@/mixins/ErrorHandling";
import { PlusIcon, Trash2Icon, MoveIcon } from 'vue-feather-icons';
import GridLayoutSelector from "@/modules/grid/components/GridLayoutSelector.vue";
import GridImageSelector from "@/modules/grid/components/GridImageSelector.vue";
import GridArticleSelector from "@/modules/grid/components/GridArticleSelector.vue";
import GridRow from "@/modules/grid/components/GridRow.vue";
import GridRowItem from "@/modules/grid/components/GridRowItem.vue";
import draggable from 'vuedraggable';
import PageHeader from "@/components/ui/PageHeader.vue";
import GridLayout from "@/modules/grid/icons/GridLayout.vue";

export default {

  components: {
    GridLayoutSelector,
    GridImageSelector,
    GridArticleSelector,
    GridRow,
    GridRowItem,
    draggable,
    PageHeader,
    PlusIcon,
    Trash2Icon,
    MoveIcon,
    GridLayout
  },

  mixins: [ErrorHandling],

  props: {

    grids: {
      type: Array,
      default: null,
    },

    model: {
      type: Object,
      default: null
    },
    
    modelName: {
      type: String,
      default: null
    }
  },

  data() {
    return {

      gridItems: [],

      currentRow: 0,
      currentItemId: 0,
      currentPos: 0,

      // Routes
      routes: {
        store: '/api/grid',
        order: '/api/grid/order',
        delete: '/api/grid',
        storeItem: '/api/grid-item',
        resetItem: '/api/grid-item',
      },

      // States
      isLoading: false,
      isFetched: true,
      isSortable: false,
      hasGridSelector: false,
      hasImageSelector: false,
      hasArticleSelector: false,
      hasArticles: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        confirm: 'Bitte löschen bestätigen!',
        updated: 'Daten aktualisiert',
        stored: 'Daten gespeichert',
        deleted: 'Daten gelöscht',
      }
    }
  },

  mounted() {
    this.gridItems = this.$props.grids;
    this.hasArticles = this.$props.modelName == 'Home' ? true : false;
  },

  methods: {

    order() {
      let grids = this.gridItems.map(function(grid, index) {
        grid.order = index;
        return grid;
      });
      if (this.debounce) return;
      this.debounce = setTimeout(function() {
      this.debounce = false;
        this.axios.post(`${this.routes.order}`, {items: grids}).then((response) => {
          this.$notify({ type: "success", text: this.messages.stored });
          this.$emit('sortedRows');
        });
      }.bind(this, grids), 500);
    },

    addRow(data) {

      let grid = {
        layout: data.layout,
        items: data.items,
        model: {
          id: this.$props.model.id,
          name: this.$props.modelName
        },
      };

      this.isLoading = true;
      this.axios.post(this.routes.store, grid).then(response => {
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
        this.hasGridSelector = false;
        this.$emit('addedRow');
      });
    },

    deleteRow(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${id}`).then(response => {
          this.$notify({ type: "success", text: this.messages.deleted });
          this.isLoading = false;
          this.$emit('deletedRow');
        });
      }
    },

    resetItem(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.put(`${this.routes.resetItem}/${id}`).then(response => {
          this.$notify({ type: "success", text: this.messages.deleted });
          this.isLoading = false;
          this.$emit('resetItem');
        });
      }
    },

    addImage(data) {
      const item = {
        id: this.currentItemId,
        position: this.currentPos,
        image_id: data.image,
        project_id: data.project
      }
      this.isLoading = true;
      this.axios.post(this.routes.storeItem, item).then(response => {
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
        this.hideImageSelector();
        this.$emit('addedRowItem');
      });
    },
    
    addArticle(data) {
      const item = {
        id: this.currentItemId,
        position: this.currentPos,
        discourse_id: data.discourse,
      }
      this.isLoading = true;
      this.axios.post(this.routes.storeItem, item).then(response => {
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
        this.hideArticleSelector();
        this.$emit('addedRowItem');
      });
    },
    
    toggleGridSelector() {
      this.hasGridSelector = this.hasGridSelector ? false : true;
    },

    toggleImageSelector() {
      this.hasImageSelector = this.hasImageSelector ? false : true;
    },

    toggleArticleSelector() {
      this.hasArticleSelector = this.hasArticleSelector ? false : true;
    },

    toggleSortable() {
      this.isSortable = this.isSortable ? false : true;
      this.gridItems = this.$props.grids;
    },

    showImageSelector(data) {
      this.currentItemId = data.id;
      this.currentPos = data.position;
      this.hasImageSelector = true;
    },

    hideImageSelector() {
      this.currentItemId = null;
      this.currentPos = null;
      this.hasImageSelector = false;
    },

    showArticleSelector(data) {
      this.currentItemId = data.id;
      this.currentPos = data.position;
      this.hasArticleSelector = true;
    },

    hideArticleSelector() {
      this.currentItemId = null;
      this.currentPos = null;
      this.hasArticleSelector = false;
    },
  },

  watch: {
    grids() {
      this.gridItems = this.$props.grids;
    }
  }

}
</script>