<template>
  <wrapper v-if="inputType !== 'hidden'"
           :inputName="inputName"
           :label="label"
           :validating="validating"
           :valid="valid"
           :errors="errors"
           :useIcons="true"
  >
    <div>Bulma Simple</div>
  </wrapper>
</template>

<script>
  import InputMixin from './_Mixin'

  export default {
    mixins: [InputMixin]
  }
</script>
