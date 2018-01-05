import Wrapper from './_Wrapper'

export default {
  props: {
    input: {
      type: Object,
      required: true
    },
    lastBlockPrefix: {
      type: String,
      required: true
    },
    formId: {
      type: String,
      required: true
    },
    disableValidation: {
      type: Boolean,
      default: false
    }
  },
  components: {
    Wrapper
  }
}
