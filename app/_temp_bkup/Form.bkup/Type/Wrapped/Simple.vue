<template>
    <wrapper v-if="lastBlockPrefix !== 'hidden'"
             :formId="formId"
             :inputName="input.vars.full_name"
             :inputId="input.vars.id"
             :label="input.vars.label"
             :useIcons="true"
    >
        <input-type v-bind="inputBind" />
    </wrapper>
    <input-type v-else
                v-bind="inputBind"
    />
</template>

<script>
  import Mixin from './_Mixin'
  import InputType from '../Input/Simple'

  export default {
    mixins: [Mixin],
    components: {
      InputType
    },
    computed: {
      inputBind () {
        return {
          formId: this.formId,
          input: this.input,
          lastBlockPrefix: this.lastBlockPrefix,
          disableValidation: this.disableValidation,
          inputClass: 'input'
        }
      }
    }
  }
</script>
