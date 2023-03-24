<template>
  <span :class="classes">
    <div><slot /></div>
  </span>
</template>

<script>
export default {
  props: ['cls'],

  computed: {
    classes() {
      if (this.$props.cls) {
        return `pill ${cls}`;
      } 
      else {
        return 'pill';
      }
    },
  }
}
</script>