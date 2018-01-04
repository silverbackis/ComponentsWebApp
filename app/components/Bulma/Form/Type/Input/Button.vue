<template>
  <button :type="lastBlockPrefix"
          v-bind="attr"
  >
    {{ input.vars.label }}
  </button>
</template>

<script>
  import InputMixin from './_Mixin'

  export default {
    mixins: [InputMixin]
  }
</script>
