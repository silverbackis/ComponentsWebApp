export default {
  mounted () {
    if (this.input && this.input.vars.multiple && typeof this.inputModel !== typeof []) {
      this.inputModel = []
    } else if (this.inputType === 'checkbox' && typeof this.inputModel !== typeof true) {
      this.inputModel = false
    }
  },
  created () {
  },
  beforeDestroy () {
  }
}
