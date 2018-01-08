<template>
    <wrapper :formId="formId" :inputName="input.vars.full_name" :inputId="input.vars.id" :label="input.vars.label">
        <input-type :formId="formId" :input="input" :lastBlockPrefix="lastBlockPrefix" :noSelectWrap="noSelectWrap" :disableValidation="disableValidation" />
    </wrapper>
</template>

<script>
  import Mixin from './_Mixin'
  import InputType from '../Input/Choice'

  export default {
    props: ['noSelectWrap'],
    mixins: [Mixin],
    components: {
      InputType
    }
  }
</script>
