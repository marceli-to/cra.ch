<template>
<div class="flex justify-start">
  <div class="items">
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
        <path d="M24 1H1V13H24V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 1H61V13H84V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M54 1H31V13H54V1Z" fill="white" stroke="black" stroke-width="2"/>
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
        <path d="M24 1H1V13H24V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M24 20H1V32H24V20Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 1H31V32H84V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '2-1_1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M84 1H61V13H84V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 20H61V32H84V20Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M54 1H1V32H54V1Z" fill="white" stroke="black" stroke-width="2"/>
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
    <div v-if="$props.layout == '1sq-1sq-1sq'">
      <svg width="85" height="25" viewBox="0 0 85 25" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M24 1H1V24H24V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 1H61V24H84V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M54 1H31V24H54V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1sq-1sq'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M39 1H1V39H39V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 1H46V39H84V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1sq-1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M39 1H1V39H39V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 1H46V54H84V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1-1sq'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M84 1H46V39H84V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M39 1H1V54H39V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1sq-1sq_1sq'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M84 1H61V24H84V1Z" fill="white" stroke="black" stroke-width="2"/>
      <path d="M84 31H61V54H84V31Z" fill="white" stroke="black" stroke-width="2"/>
      <path d="M54 1H1V54H54V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1sq_1sq-1sq'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M24 1H1V24H24V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M24 31H1V54H24V31Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 1H31V54H84V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1sq-1_1_1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M84 1H61V13H84V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 21H61V34H84V21Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 42H61V54H84V42Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M54 1H1V54H54V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>

    </div>
    <div v-if="$props.layout == '1_1_1-1sq'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M24 1H1V13H24V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M24 21H1V34H24V21Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M24 42H1V54H24V42Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 1H31V54H84V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1sq-1_1'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M84 42H61V54H84V42Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 1H61V35H84V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M54 1H1V54H54V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
    <div v-if="$props.layout == '1_1-1sq'">
      <svg width="85" height="55" viewBox="0 0 85 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M24 42H1V54H24V42Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M24 1H1V35H24V1Z" fill="white" stroke="black" stroke-width="2"/>
        <path d="M84 1H31V54H84V1Z" fill="white" stroke="black" stroke-width="2"/>
      </svg>
    </div>
  </div>
  <template v-if="$props.items">
    <div class="ml-6x flex">
      <div v-for="item in $props.items" :key="item.id">
        <img 
          :src="getSource(item.image, 'crop')" 
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
