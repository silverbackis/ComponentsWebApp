<template>
  <label :class="labelClass" :for="input.vars.id">
    <input v-model="inputModel"
           v-bind="commonProps"
           :type="'checkbox'"
    >
    <div class="indicator" v-if="isCustom"></div>
    <span class="input-label" v-html="input.vars.label"></span>
  </label>
</template>

<script>
  import InputCommonMixin from './_CommonMixin'
  import InputMixin from './Mixin'

  export default {
    mixins: [InputCommonMixin, InputMixin],
    data () {
      return {
        checked: []
      }
    },
    computed: {
      isCustom () {
        return this.input.vars.attr.class && this.input.vars.attr.class.indexOf('custom') !== -1
      },
      labelClass () {
        return {
          checkbox: true,
          custom: this.isCustom
        }
      }
    }
  }
</script>

<style lang="sass" src="~/assets/css/components/bulma_checkbox_radio.sass" />
