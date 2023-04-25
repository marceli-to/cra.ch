<template>
<div class="flex justify-start">
  <div>
    <div v-if="$props.layout == '1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="1" y="1" width="83" height="53" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '2-1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="1" y="1" width="53" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1-2'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="31" y="1" width="53" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1-1-1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="31" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1w-1w-1w'">
      <svg width="85" height="25" viewBox="0 0 85 25" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="1" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="31" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
      </svg>    
    </div>
    <div v-if="$props.layout == '1-1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="1" y="1" width="38" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="46" y="1" width="38" height="53" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1w-1w'">
      <svg width="85" height="25" viewBox="0 0 85 25" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="1" y="1" width="38" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="46" y="1" width="38" height="23" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1_1-2'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="1" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="31" y="1" width="53" height="53" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '2-1_1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="61" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="53" height="53" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1-1_1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="46" y="1" width="38" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="46" y="31" width="38" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="38" height="53" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1_1-1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="1" y="1" width="38" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="31" width="38" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="46" y="1" width="38" height="53" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1-1-1_1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="1" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="31" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>

    <div v-if="$props.layout == '1_1-1-1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="31" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>

    <div v-if="$props.layout == '1-1_1-1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="31" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="31" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1_1-1-1_1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="61" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="31" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1_1-1_1-1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="31" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="31" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1-1_1-1_1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="61" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="31" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="31" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1t_1-1_1-1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="31" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="61" y="1" width="23" height="53" fill="white" stroke="black" stroke-width="2"/>
        <rect x="31" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="1" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="1" y="31" width="23" height="23" fill="white" stroke="black" stroke-width="2"/>
        <rect x="5" y="8" width="15" height="2" fill="#666666"/>
        <rect x="5" y="16" width="15" height="2" fill="#666666"/>
        <rect x="5" y="12" width="15" height="2" fill="#666666"/>
      </svg>
    </div>
  </div>
  <template v-if="$props.items">
    <div class="ml-6x flex">
      <div v-for="item in $props.items" :key="item.id">
        <img 
          :src="getSource(item.image, 'cache')" 
          height="300" 
          width="300"
          style="height: 50px; width: auto; display: block; margin: 0 4px"
          v-if="item.image">        
      </div>
    </div>
  </template>
</div>
</template>
<script>
import ImageUtils from "@/modules/images/mixins/utils";

export default {

  mixins: [ImageUtils],
  
  props: {
    layout: {
      type: String,
      default: '1'
    },

    items: {
      type: Array,
    }
  },
}
</script>
