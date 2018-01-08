<template>
  <div>
    <radio-option v-for="(choice, choiceIndex) in input.vars.choices"
                  :key="choiceIndex"
                  :choice="choice"
                  :formId="formId"
    />
  </div>
</template>

<script>
  import InputCommonMixin from './_CommonMixin'
  import RadioOption from './_ChoiceRadioOption'

  export default {
    mixins: [InputCommonMixin],
    components: {
      RadioOption
    }
  }
</script>
