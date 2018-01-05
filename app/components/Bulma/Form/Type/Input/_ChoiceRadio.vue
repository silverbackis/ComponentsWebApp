<template>
  <div>
    <radio-option v-for="(choice, choiceIndex) in input.children"
                  :key="choiceIndex"
                  :input="choice"
                  :formId="formId"
    />
  </div>
</template>

<script>
  import RadioOption from './_ChoiceRadioOption'

  export default {
    props: ['input', 'formId'],
    components: {
      RadioOption
    }
  }
</script>
