<template>
  <input type="text"
         v-bind="commonProps"
         v-on="events"
         v-model="inputModel"
  />
</template>

<script>
  import InputCommonMixin from './_CommonMixin'
  import InputMixin from './Mixin'

  export default {
    mixins: [InputCommonMixin, InputMixin],
    data () {
      return {
        events: {
          blur: this.inputBlur,
          'keypress.enter': this.inputBlur
        }
      }
    },
    computed: {
      commonProps () {
        return Object.assign(this.attr, {
          id: this.inputId,
          name: this.inputName,
          class: this.classes
        })
      }
    }
  }
</script>
