<template>
  <choice-radio v-if="input.vars.expanded"
                :input="input"
                :formId="formId" />
  <choice-select v-else
                 :input="input"
                 :formId="formId"
                 :lastBlockPrefix="lastBlockPrefix"
                 :noSelectWrap="noSelectWrap"
                 :instantUpdate="true"
  ></choice-select>
</template>

<script>
  import Mixin from '../Wrapped/_Mixin'
  import ChoiceSelect from './_ChoiceSelect'
  import ChoiceRadio from './_ChoiceRadio'

  export default {
    props: ['noSelectWrap', 'inputClass'],
    mixins: [Mixin],
    components: {
      ChoiceSelect,
      ChoiceRadio
    }
  }
</script>
