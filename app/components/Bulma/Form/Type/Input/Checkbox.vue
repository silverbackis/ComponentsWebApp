<template>
  <label :class="labelClass" :for="vars.id">
    <input type="checkbox"
           v-model="modelValue"
           v-bind="attr"
    >
    <div class="indicator" if="isCustom"></div>
    <span class="input-label" v-html="input.vars.label"></span>
  </label>
</template>

<script>
  import InputMixin from './_Mixin'

  export default {
    mixins: [InputMixin],
    computed: {
      labelClass () {
        return [
          'checkbox',
          this.isCustom ? 'custom' : ''
        ]
      }
    }
  }
</script>
