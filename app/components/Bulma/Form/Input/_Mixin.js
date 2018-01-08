import { mapGetters } from 'vuex'
import Wrapper from './_Wrapper'

export default {
  components: {
    Wrapper
  },
  props: {
    formId: {
      type: String,
      required: true
    },
    inputName: {
      type: String,
      required: true
    },
    inputType: {
      type: String,
      require: true
    }
  },
  computed: {
    ...mapGetters({
      getInput: 'forms/getInput'
    }),
    input () {
      return this.getInput(this.formId, this.inputName)
    },
    label () {
      return this.input.vars.label
    },
    errors () {
      return this.input.vars.errors
    },
    valid () {
      return this.input.vars.valid
    },
    validating () {
      return this.input.validating
    }
  }
}
