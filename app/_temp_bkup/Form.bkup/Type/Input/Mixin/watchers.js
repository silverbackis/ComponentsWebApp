export default {
  watch: {
    // Properties that need to be adjusted directly from this component
    // But also needs to be known to other components (e.g. the input wrapper)
    displayErrors () {
      this.setInputDisplayErrors(
        this.extendModelIds({
          displayErrors: this.displayErrors
        })
      )
    },
    input () {
      this.vars = this.input.vars
    }
  }
}
