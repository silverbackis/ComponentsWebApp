<template>
  <label :class="labelClass" :for="vars.id">
    <input type="radio"
           v-model="modelValue"
           v-bind="attr"
           v-bind:value="inputValue"
    >
    <div class="indicator" v-if="isCustom"></div>
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
          'radio',
          this.isCustom ? 'custom' : ''
        ]
      }
    }
  }
</script>
