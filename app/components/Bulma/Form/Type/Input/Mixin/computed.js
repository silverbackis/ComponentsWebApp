import { mapGetters } from 'vuex'
import _ from 'lodash'

export default {
  computed: {
    ...mapGetters({
      getInputAxiosCancelToken: 'forms/getInputAxiosCancelToken',
      getFormAction: 'forms/getFormAction',
      getFormAxiosCancelFn: 'forms/getFormAxiosCancelFn',
      getInput: 'forms/getInput',
      getInputValue: 'forms/getInputValue',
      getInputValid: 'forms/getInputValid',
      getInputCurrentErrors: 'forms/getInputCurrentErrors',
      getInputSubmitData: 'forms/getInputSubmitData',
      getFormSubmitting: 'forms/getFormSubmitting',
      getInputValidating: 'forms/getInputValidating'
    }),
    vars () {
      return this.input.vars
    },
    inputName () {
      return this.vars.full_name
    },
    inputValue () {
      return this.vars.value
    },
    inputLabel () {
      return this.vars.label
    },
    storeInput () {
      return this.getInput(this.formId, this.inputName)
    },
    cancelToken () {
      return this.getInputAxiosCancelToken(this.formId, this.inputName)
    },
    modelValue: {
      get () {
        if (this.isCheckRadio || this.instantUpdate) {
          // radios need to have a model which has a central storage so they both stay up to date
          // they are constructed with different components
          return this.getInputValue(this.formId, this.inputName)
        } else {
          return this.vars.value
        }
      },
      set (value) {
        this.vars.value = value
        this.setValidating(!this.disableValidation)

        if (this.isCheckRadio || this.instantUpdate) {
          return this.setStoreValue(value)
        } else {
          if (this.debounce.modelValue) {
            this.debounce.modelValue.cancel()
          }
          this.debounce.modelValue = _.debounce(() => {
            this.setStoreValue(value)
          }, 350)
          return this.debounce.modelValue()
        }
      }
    },
    isCheckRadio () {
      return this.vars.checked !== undefined
    },
    isRadio () {
      return this.vars.block_prefixes[2] === 'radio'
    },
    isCustom () {
      return this.attr.class.indexOf('custom') !== -1
    },
    attr () {
      let attr = Object.assign({}, this.input.vars.attr, {
        id: this.vars.id,
        name: this.inputName,
        required: this.vars.required,
        disabled: this.vars.disabled || this.getFormSubmitting(this.formId)
      })
      if (this.isCheckRadio) {
        attr.checked = this.vars.checked
      }
      attr.class = [...[this.inputClass], ...this.inputClassAuto]
      return attr
    },
    inputClassAuto () {
      let classes = []
      if (undefined !== this.vars.attr['class']) {
        classes.push(this.vars.attr['class'])
      }
      let valid = this.getInputValid(this.formId, this.inputName)
      if (valid !== null && valid !== undefined) {
        if (valid === true) {
          classes.push('is-success')
          this.displayErrors = true
        } else if (this.getInputCurrentErrors(this.formId, this.inputName)) {
          classes.push('is-danger')
        }
      }
      return classes
    }
  }
}
