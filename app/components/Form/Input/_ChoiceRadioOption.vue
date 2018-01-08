<template>
  <label :class="labelClass" :for="child.vars.id">
    <input type="radio"
           v-model="inputModel"
           v-bind="commonProps"
           :id="child.vars.id"
           :class="child.vars.attr.class"
           :value="child.vars.value"
    />
    <div class="indicator" v-if="isCustom"></div>
    <span class="input-label" v-html="child.vars.label"></span>
  </label>
</template>

<script>
  import InputCommonMixin from './_CommonMixin'
  import InputMixin from './Mixin'

  export default {
    mixins: [InputCommonMixin, InputMixin],
    props: {
      index: {
        type: Number,
        required: true
      }
    },
    computed: {
      child () {
        return this.input.children[this.index]
      },
      isCustom () {
        return this.child.vars.attr.class && this.child.vars.attr.class.indexOf('custom') !== -1
      },
      labelClass () {
        return {
          radio: true,
          custom: this.isCustom
        }
      }
    }
  }
</script>

<style lang="sass">
  @import '~assets/css/components/bulma_checkbox_radio'

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
