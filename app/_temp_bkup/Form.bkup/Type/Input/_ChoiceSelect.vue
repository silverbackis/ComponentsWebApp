<template>
  <span v-if="!noSelectWrap"
        :class="selectWrapperClass"
  >
    <select v-model="modelValue"
            v-bind="attr"
    >
      <select-option v-for="(choice, choiceIndex) in input.vars.choices"
                     :key="choiceIndex"
                     :option="choice"
      />
    </select>
  </span>
  <select v-else
          v-model="modelValue"
          v-bind="attr"
  >
    <select-option v-for="(choice, choiceIndex) in input.vars.choices"
                   :key="choiceIndex"
                   :option="choice" />
  </select>
</template>

<script>
  import InputMixin from './Mixin'
  import SelectOption from './_ChoiceSelectOption'

  export default {
    props: ['noSelectWrap'],
    mixins: [InputMixin],
    components: {
      SelectOption
    },
    computed: {
      selectWrapperClass () {
        return [...['select'], ...this.attr.class]
      }
    }
  }
</script>

<style lang="sass">
  .select
    width: 100%
    select
      width: 100%
</style>
