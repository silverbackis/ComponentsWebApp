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
  import InputMixin from './Mixin'
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

<style lang="sass">
  .radio
    .custom
      ~ .indicator
        border-radius: 50%
        &::after
          border-radius: 50%
      &:checked
        ~ .indicator
          &::after
            border-radius: 50%
</style>
