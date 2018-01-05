export default {
  mounted () {
    let vars = Object.assign({}, this.vars)
    if (this.storeInput.displayErrors === null) {
      this.initInput(this.extendModelIds({
        label: this.inputLabel,
        displayErrors: this.displayErrors,
        validating: this.validating
      }))

      if (this.lastBlockPrefix === 'checkbox') {
        this.setInputValue(this.extendModelIds({
          value: vars.checked
        }))
      } else if (!this.isCheckRadio || vars.checked) {
        this.setInputValue(this.extendModelIds({
          value: this.inputValue
        }))
      }
      // Initial inputs come through from api as valid (incorrectly)
      vars.valid = null
    } else {
      this.displayErrors = this.storeInput.displayErrors
      vars.valid = this.storeInput.valid
      vars.errors = this.storeInput.errors
      // Checkbox and radios have their own value which should not be changed
      if (!this.isCheckRadio) {
        vars.value = this.storeInput.value
      }
    }
    this.vars = vars
  },
  created () {
    // Non reactive
    this.debounce = {
      // validate: null,
      modelValue: null
    }
  },
  beforeDestroy () {
    this.destroying = true
    for (let dbK of Object.keys(this.debounce)) {
      if (this.debounce[dbK]) {
        this.debounce[dbK].cancel()
      }
    }
    if (this.cancelToken) {
      this.cancelToken.cancel('input destroyed')
    }
  }
}
