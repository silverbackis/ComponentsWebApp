export default {
  props: {
    input: {
      type: Object,
      required: true
    },
    formId: {
      type: String,
      required: true
    },
    lastBlockPrefix: {
      type: String,
      required: false
    },
    inputClass: {
      type: String,
      required: false,
      default: ''
    },
    instantUpdate: {
      type: Boolean,
      default: false,
      required: false
    },
    disableValidation: {
      type: Boolean,
      default: false
    }
  }
}
