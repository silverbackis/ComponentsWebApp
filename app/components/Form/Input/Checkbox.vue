<template>
  <label :class="labelClass" :for="input.vars.id">
    {{checked}}
    {{inputModel}}
    <input type="checkbox"
           v-model="checked"
           v-bind="commonProps"
           :value="input.vars.value"
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

<style lang="sass">
  .checkbox
    margin-top: .5rem
    .custom
      &:checked
        ~ .indicator
          &::after
            transform: scale(.6)
            background-color: transparent
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3E%3Cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3E%3C/svg%3E")
</style>
