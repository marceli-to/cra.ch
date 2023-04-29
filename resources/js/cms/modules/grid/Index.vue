<template>
<div v-if="isFetched">

  <page-header>
    <h1 v-if="$props.modelName == 'Project'">{{ $props.model.title }}</h1>
    <h1 v-else-if="$props.modelName == 'Diary'">Tagebuch</h1>
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

  <grid-gallery-selector 
    :modelName="$props.modelName"
    :layouts="$props.layouts"
    @select="addRow($event)" 
    v-if="hasGridSelector">
  </grid-gallery-selector>

  <grid-image-selector
    :model="$props.model"
    :modelName="$props.modelName"
    @close="toggleImageSelector()"
    @select="addImage($event)"
    v-if="hasImageSelector">
  </grid-image-selector>  

  <grid-article-selector
    @close="toggleArticleSelector()"
    @select="addArticle($event)"
    v-if="hasArticleSelector">
  </grid-article-selector>  

  <template v-if="!isSortable">
    <grid-row 
      :key="grid.id" 
      v-for="grid in $props.grids" 
      :class="`grid-gallery grid-gallery-${grid.layout}`">
      <a 
        href="javascript:;" 
        class="btn-delete has-icon"
        @click.prevent="deleteRow(grid.id)">
        <trash-2-icon size="16"></trash-2-icon>
        <span>Zeile löschen</span>
      </a>
      <div>
        <template v-if="grid.layout == '1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-a'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>  
        </template>
        <template v-if="grid.layout == '2-1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-e'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-f'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>    
        </template>
        <template v-if="grid.layout == '1-2'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-f'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-e'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>    
        </template>
        <template v-if="grid.layout == '1-1-1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-f'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-f'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-f'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>     
        </template>
        <template v-if="grid.layout == '1w-1w-1w'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-g'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-g'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-g'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>     
        </template>
        <template v-if="grid.layout == '1-1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>  
        </template>
        <template v-if="grid.layout == '1w-1w'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>  
        </template>
        <template v-if="grid.layout == '1_1-2'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-g'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-g'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-e'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>  
        </template>
        <template v-if="grid.layout == '2-1_1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-e'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>  
        </template>
        <template v-if="grid.layout == '1-1_1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>  
        </template>
        <template v-if="grid.layout == '1_1-1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>  
        </template>
        <template v-if="grid.layout == '1-1-1_1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[3] ? grid.grid_items[3] : null"
            :area="'grid-area-d'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>    
        </template>
        <template v-if="grid.layout == '1_1-1-1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>  
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[3] ? grid.grid_items[3] : null"
            :area="'grid-area-d'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
        </template>
        <template v-if="grid.layout == '1-1_1-1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[3] ? grid.grid_items[3] : null"
            :area="'grid-area-d'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>    
        </template>
        <template v-if="grid.layout == '1_1-1-1_1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[3] ? grid.grid_items[3] : null"
            :area="'grid-area-d'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[4] ? grid.grid_items[4] : null"
            :area="'grid-area-e'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>     
        </template>
        <template v-if="grid.layout == '1_1-1_1-1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[3] ? grid.grid_items[3] : null"
            :area="'grid-area-d'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[4] ? grid.grid_items[4] : null"
            :area="'grid-area-e'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>     
        </template>
        <template v-if="grid.layout == '1t_1-1_1-1'">
          <div class="grid-area-a aspect-ratio-c p-2x">
            <figure class="text-xs" style="flex-direction: column; align-items: flex-start;" v-html="$props.model.articleContent">
            </figure> 
          </div>
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-d'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[3] ? grid.grid_items[3] : null"
            :area="'grid-area-e'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>          
        </template>
        <template v-if="grid.layout == '1-1_1-1_1'">
          <grid-item
            :item="grid.grid_items[0] ? grid.grid_items[0] : null"
            :area="'grid-area-a'"
            :ratio="'aspect-ratio-b'"
            :hasArticles="false"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[1] ? grid.grid_items[1] : null"
            :area="'grid-area-b'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item> 
          <grid-item
            :item="grid.grid_items[2] ? grid.grid_items[2] : null"
            :area="'grid-area-c'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[3] ? grid.grid_items[3] : null"
            :area="'grid-area-d'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>
          <grid-item
            :item="grid.grid_items[4] ? grid.grid_items[4] : null"
            :area="'grid-area-e'"
            :ratio="'aspect-ratio-c'"
            :hasArticles="hasArticles"
            @resetItem="resetItem($event)"
            @showImages="showImageSelector($event)"
            @showArticles="showArticleSelector($event)">
          </grid-item>     
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
        <grid-gallery-icon :layout="grid.layout" :items="grid.grid_items" :isOrderView="true" />
      </div> 
    </draggable>
  </template>
</div>
</template>
<script>
import ErrorHandling from "@/mixins/ErrorHandling";
import { PlusIcon, Trash2Icon, MoveIcon } from 'vue-feather-icons';
import GridGallerySelector from "@/modules/grid/components/GridGallerySelector.vue";
import GridImageSelector from "@/modules/grid/components/GridImageSelector.vue";
import GridArticleSelector from "@/modules/grid/components/GridArticleSelector.vue";
import GridRow from "@/modules/grid/components/GridRow.vue";
import GridItem from "@/modules/grid/components/GridItem.vue";
import draggable from 'vuedraggable';
import PageHeader from "@/components/ui/PageHeader.vue";
import GridGalleryIcon from "@/modules/grid/icons/GridGalleryIcon.vue";

export default {

  components: {
    GridGallerySelector,
    GridImageSelector,
    GridArticleSelector,
    GridRow,
    GridItem,
    GridGalleryIcon,
    draggable,
    PageHeader,
    PlusIcon,
    Trash2Icon,
    MoveIcon
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
    },

    layouts: {
      type: Array,
      default: () => [],
    },
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
        project_id: data.project ? data.project : null,
        page: data.page ? data.page : null,
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
        article_id: data.article,
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