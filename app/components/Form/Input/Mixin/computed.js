import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      getForm: 'forms/getForm'
    }),
    form () {
      return this.getForm(this.formId)
    },
    action () {
      return this.form.vars.action
    },
    attr () {
      return Object.assign({}, this.input.vars.attr, {
        required: this.input.vars.required,
        disabled: this.input.vars.disabled || this.form.submitting
      })
    },
    classes () {
      let classes = [this.inputClass]
      // could have classes assigned from API side (this will be a string)
      let apiClasses = this.input.vars.attr['class']
      if (undefined !== apiClasses) {
        classes.push(apiClasses)
      }
      if (this.valid) {
        classes.push('is-success')
        this.displayErrors = true
      } else if (this.displayErrors) {
        classes.push('is-danger')
      }
      return classes
    },
    isCheckRadio () {
      return this.input.vars.checked !== undefined || this.child
    },
    inputModel: {
      get () {
        return this.input.vars.value
      },
      set (value) {
        this.setInputValue(this.extendInputId({
          value
        }))
        this.beginValidation()
      }
    },
    validating: {
      get () {
        return this.input.validating
      },
      set (validating) {
        this.setInputValidating(
          this.extendInputId({ validating })
        )
      }
    },
    commonProps () {
      return Object.assign(this.attr, {
        id: this.inputId,
        name: this.inputName,
        class: this.classes
      })
    },
    /**
     * These need to be in the store because individual radios should share the debounce/cancel tokens and
     * last validation values but are outputted as child inputs/components
     */
    debounceValidate: {
      get () {
        return this.input.debounceValidate
      },
      set (debounce) {
        this.setInputDebounceValidate(
          this.extendInputId({ debounce })
        )
      }
    },
    cancelToken: {
      get () {
        return this.input.cancelToken
      },
      set (token) {
        this.setInputCancelToken(
          this.extendInputId({ token })
        )
      }
    },
    lastValidationValue: {
      get () {
        return this.input.lastValidationValue
      },
      set (value) {
        this.setInputLastValidationValue(
          this.extendInputId({ value })
        )
      }
    }
  }
}
