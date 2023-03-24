<template>
  <div :class="`chip ${cls}`">
    <slot />
  </div>
</template>
<script>
export default {
  props: {
    cls: {
      type: String,
      default: ''
    }
  }
}
</script>