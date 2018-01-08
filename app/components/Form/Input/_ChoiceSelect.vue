<template>
  <select v-bind="commonProps"
          v-model="inputModel"
  >
    <select-option v-for="(choice, choiceIndex) in input.vars.choices"
                   :key="choiceIndex"
                   :option="choice" />
  </select>
</template>

<script>
  import InputCommonMixin from './_CommonMixin'
  import InputMixin from './Mixin'
  import SelectOption from './_ChoiceSelectOption'

  export default {
    mixins: [InputCommonMixin, InputMixin],
    components: {
      SelectOption
    }
  }
</script>

<style lang="sass">
  .select
    width: 100%
    select
      width: 100%
</style>
