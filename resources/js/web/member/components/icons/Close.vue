<template>
  <svg width="57" height="57" viewBox="0 0 57 57" fill="none" xmlns="http://www.w3.org/2000/svg" :class="$props.cls">
    <circle cx="28.5" cy="28.5" r="26" fill="transparent" stroke="#FF6A00" stroke-width="5"/>
    <path stroke="#FF6A00" stroke-width="4" stroke-linecap="round" d="M16 39.172L39.172 16M16 39.172L39.172 16M16 39.172L39.172 16M39.172 40L16 16.828M39.172 40L16 16.828M39.172 40L16 16.828"/>
  </svg>
</template>
<script>
export default {
  props: {
    cls: {
      type: String,
    },
  }      
}
</script>
<style>
.has-bg circle {
  fill: #ffffff;
}
</style>

