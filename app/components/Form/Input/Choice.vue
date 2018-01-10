<template>
  <div>
    <choice-select v-if="!input.vars.expanded"
                   :formId="formId"
                   :inputName="inputName"
    />
    <choice-expanded v-else
                  :formId="formId"
                  :inputName="inputName"
    />
  </div>
</template>

<script>
  import InputCommonMixin from './_CommonMixin'
  import ChoiceSelect from './Choice/ChoiceSelect'
  import ChoiceExpanded from './Choice/ChoiceExpanded'

  export default {
    mixins: [InputCommonMixin],
    components: {
      ChoiceSelect,
      ChoiceExpanded
    },
    created () {
      this.displayErrors = true
    }
  }
</script>
