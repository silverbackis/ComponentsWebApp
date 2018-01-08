<template>
  <wrapper v-bind="wrapperData"
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
  import FormInput from '~/components/Form/Input/Textarea'

  export default {
    mixins: [InputMixin],
    components: {
      FormInput
    }
  }
</script>
