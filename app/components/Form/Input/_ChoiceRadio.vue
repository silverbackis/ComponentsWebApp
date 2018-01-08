<template>
  <div>
    <radio-option v-for="(choice, choiceIndex) in input.children"
                  :key="choiceIndex"
                  :index="choiceIndex"
                  :inputName="choice.vars.full_name"
                  :formId="formId"
                  inputClass=""
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
