<template>
  <wrapper v-bind="wrapperData"
           :useIcons="false"
  >
    <form-input :formId="formId"
                :inputName="inputName"
                :inputType="inputType"
                inputClass=""
    />
  </wrapper>
</template>

<script>
  import InputMixin from './_Mixin'
  import FormInput from '~/components/Form/Input/Checkbox'

  export default {
    mixins: [InputMixin],
    components: {
      FormInput
    }
  }
</script>
