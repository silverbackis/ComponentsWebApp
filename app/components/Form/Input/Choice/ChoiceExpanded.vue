<template>
  <div v-if="input">
    {{ input.children }}
    <component  :is="is"
                v-for="(choice, choiceIndex) in input.children"
                :key="choiceIndex"
                :index="choiceIndex"
                :inputName="choice.vars.full_name"
                :formId="formId"
                inputClass=""
    />
  </div>
</template>

<script>
  import InputCommonMixin from '../_CommonMixin'
  import Checkbox from '~/components/Form/Input/Checkbox'
  import Radio from '~/components/Form/Input/Radio'

  export default {
    mixins: [InputCommonMixin],
    data () {
      return {
        is: 'radio'
      }
    },
    components: {
      Checkbox,
      Radio
    }
  }
</script>
