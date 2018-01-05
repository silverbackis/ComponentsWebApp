export default {
  data () {
    return {
      displayErrors: this.instantUpdate || false,
      lastValidationValue: null,
      validating: false,
      destroying: false
    }
  }
}
