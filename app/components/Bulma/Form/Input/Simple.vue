<template>
  <wrapper v-if="inputType !== 'hidden'"
           :inputId="inputId"
           :label="label"
           :validating="validating"
           :valid="valid"
           :errors="errors"
           :displayErrors="displayErrors"
           :useIcons="true"
  >
    <form-input :formId="formId"
                :inputName="inputName"
                :inputType="inputType"
    />
  </wrapper>
</template>

<script>
  import InputMixin from './_Mixin'
  import FormInput from '~/components/Form/Input/Simple'

  export default {
    mixins: [InputMixin],
    components: {
      FormInput
    }
  }
</script>
