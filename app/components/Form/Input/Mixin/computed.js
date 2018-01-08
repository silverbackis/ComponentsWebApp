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
        disabled: this.input.vars.disabled
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
      return this.input.vars.checked !== undefined
    },
    isRadio () {
      return this.input.vars.block_prefixes[2] === 'radio'
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
    }
  }
}
