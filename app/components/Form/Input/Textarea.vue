<template>
  <textarea v-model="inputModel"
            v-bind="commonProps"
            v-on="events"
  ></textarea>
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
    }
  }
</script>

<style>
  textarea {
    display: block;
    resize: vertical;
    max-width: 100%;
    min-width: 100%;
    max-height: 600px;
    min-height: 120px;
  }
</style>
