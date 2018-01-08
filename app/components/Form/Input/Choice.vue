<template>
  <choice-select v-if="!input.vars.expanded"
                 :formId="formId"
                 :inputName="inputName"
  />
  <choice-radio v-else
                :formId="formId"
                :inputName="inputName"
  />
</template>

<script>
  import InputCommonMixin from './_CommonMixin'
  import ChoiceSelect from './_ChoiceSelect'
  import ChoiceRadio from './_ChoiceRadio'

  export default {
    mixins: [InputCommonMixin],
    components: {
      ChoiceSelect,
      ChoiceRadio
    },
    created () {
      this.displayErrors = true
    }
  }
</script>
