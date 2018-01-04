<template>
    <wrapper :formId="formId" :inputName="input.vars.full_name" :label="input.vars.label" :inputId="input.vars.id" :useIcons="true">
        <input-type :formId="formId" :input="input" :lastBlockPrefix="lastBlockPrefix" :disableValidation="disableValidation" inputClass="textarea" />
    </wrapper>
</template>

<script>
  import Mixin from './_Mixin'
  import InputType from '../Input/Textarea'

  export default {
    mixins: [Mixin],
    components: {
      InputType
    },
    computed: {
      controlTag () {
        return 'p'
      }
    }
  }
</script>
