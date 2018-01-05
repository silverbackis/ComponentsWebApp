<template>
  <textarea v-model="modelValue"
            v-bind="attr"
            @blur="inputBlur()"
  >{{ input.vars.value }}</textarea>
</template>

<script>
  import InputMixin from './Mixin'

  export default {
    mixins: [InputMixin]
  }
</script>
