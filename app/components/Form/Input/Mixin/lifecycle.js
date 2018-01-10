export default {
  mounted () {
    if (this.inputType === 'checkbox' && typeof this.inputModel !== typeof true) {
      if (!this.input.vars.expanded) {
        this.inputModel = false
      }
    }
  },
  created () {
  },
  beforeDestroy () {
  }
}
