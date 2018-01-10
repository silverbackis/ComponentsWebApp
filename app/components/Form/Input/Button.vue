<template>
  <button :type="inputType"
          v-bind="commonProps"
  >
    {{ input.vars.label }}
  </button>
</template>

<script>
  import InputCommonMixin from './_CommonMixin'
  import InputMixin from './Mixin'

  export default {
    mixins: [InputCommonMixin, InputMixin]
  }
</script>
