export default {
  mounted () {
    if (this.storeInput.displayErrors === null) {
      this.initInput(this.extendModelIds({
        label: this.inputLabel,
        displayErrors: this.displayErrors,
        validating: this.validating
      }))
      if (this.lastBlockPrefix === 'checkbox') {
        this.setInputValue(this.extendModelIds({
          value: this.vars.checked
        }))
      } else if (!this.isCheckRadio || this.vars.checked) {
        this.setInputValue(this.extendModelIds({
          value: this.inputValue
        }))
      }
      // Initial inputs come through from api as valid (incorrectly)
      this.vars.valid = null
    } else {
      this.validating = this.storeInput.validating
      this.displayErrors = this.storeInput.displayErrors
      this.vars.valid = this.storeInput.valid
      this.vars.errors = this.storeInput.errors
      // Checkbox and radios have their own value which should not be changed
      if (!this.isCheckRadio) {
        this.vars.value = this.storeInput.value
      }
    }
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
  }
}
