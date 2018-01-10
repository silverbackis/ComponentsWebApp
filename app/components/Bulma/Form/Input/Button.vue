<template>
  <wrapper v-if="inputType !== 'hidden'"
           v-bind="wrapperData"
           :useIcons="false"
           label=""
           :errors="[]"
  >
    <form-input :formId="formId"
                :inputName="inputName"
                :inputType="inputType"
                inputClass="button"
    />
  </wrapper>
</template>

<script>
  import InputMixin from './_Mixin'
  import FormInput from '~/components/Form/Input/Button'

  export default {
    mixins: [InputMixin],
    components: {
      FormInput
    }
  }
</script>
