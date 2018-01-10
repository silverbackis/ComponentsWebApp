<template>
  <div v-if="input">
    <choice-expanded-option
                v-for="(choice, choiceIndex) in input.children"
                :key="choiceIndex"
                :index="choiceIndex"
                :inputName="inputName"
                :formId="formId"
                inputClass=""
    />
  </div>
</template>

<script>
  import InputCommonMixin from '../_CommonMixin'
  import ChoiceExpandedOption from './Option/ChoiceExpandedOption'

  export default {
    mixins: [InputCommonMixin],
    data () {
      return {
        is: 'radio'
      }
    },
    components: {
      ChoiceExpandedOption
    }
  }
</script>
