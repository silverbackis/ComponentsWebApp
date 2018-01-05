<template>
    <wrapper :formId="formId">
        <input-type :formId="formId" :input="input" :lastBlockPrefix="lastBlockPrefix" :disableValidation="disableValidation" inputClass="button" />
    </wrapper>
</template>

<script>
  import Mixin from './_Mixin'
  import InputType from '../Input/Button'

  export default {
    mixins: [Mixin],
    components: {
      InputType
    }
  }
</script>
