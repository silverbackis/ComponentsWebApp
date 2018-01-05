<template>
    <wrapper :formId="formId" :inputName="input.vars.full_name" :inputId="input.vars.id">
        <input-type :formId="formId" :input="input" :lastBlockPrefix="lastBlockPrefix" :disableValidation="disableValidation" />
    </wrapper>
</template>

<script>
  import Mixin from './_Mixin'
  import InputType from '../Input/Checkbox'

  export default {
    mixins: [Mixin],
    components: {
      InputType
    }
  }
</script>
