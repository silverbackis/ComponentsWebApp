import { mapMutations, mapGetters } from 'vuex'
import _ from 'lodash'
import axios from 'axios'

export default {
  data () {
    return {
      displayErrors: this.instantUpdate || false,
      lastValidationValue: null,
      validating: false,
      destroying: false
    }
  },
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
  },
  computed: {
    ...mapGetters({
      getFormAxiosCancelToken: 'forms/getFormAxiosCancelToken',
      getFormAction: 'forms/getFormAction',
      getFormAxiosCancelFn: 'forms/getFormAxiosCancelFn',
      getInput: 'forms/getInput',
      getInputValue: 'forms/getInputValue',
      getInputValid: 'forms/getInputValid',
      getInputCurrentErrors: 'forms/getInputCurrentErrors',
      getInputSubmitData: 'forms/getInputSubmitData',
      getFormSubmitting: 'forms/getFormSubmitting'
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
    inputLastValidation () {
      return this.storeInput.lastValidationValue
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
        this.validating = !this.disableValidation
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
  },
  methods: {
    ...mapMutations({
      initInput: 'forms/initInput',
      setInputValue: 'forms/setInputValue',
      setInputValidating: 'forms/setInputValidating',
      setInputValid: 'forms/setInputValid',
      setInputDisplayErrors: 'forms/setInputDisplayErrors'
    }),
    setStoreValue (value) {
      this.setInputValue(this.extendModelIds({
        value: value
      }))
      this.validate()
    },
    extendModelIds (data) {
      return Object.assign(
        {
          formId: this.formId,
          inputName: this.inputName
        },
        data
      )
    },
    inputBlur () {
      if (!this.destroying) {
        if (!(this.isCheckRadio || this.instantUpdate)) {
          this.setStoreValue(this.vars.value)
        }
        this.displayErrors = true
        this.validate()
      }
    },
    async validate () {
      if (!this.disableValidation) {
        if (this.lastValidationValue !== this.modelValue) {
          let obj = this.getInputSubmitData(this.formId, this.vars.full_name)
          // lastValidationValue needs to be shared
          this.lastValidationValue = this.modelValue
          try {
            let { data } = await this.$axios.request({
              url: '/forms/submit/9',
              data: obj,
              method: 'PATCH',
              cancelToken: this.getFormAxiosCancelToken(this.formId)
            })
            this.setInputValid(this.extendModelIds({
              valid: data.form.vars.valid,
              errors: data.form.vars.errors
            }))
            console.log('resolve successful validation')
            this.validating = false
          } catch (error) {
            if (!axios.isCancel(error)) {
              if (error.response.status === 406) {
                // Invalid
                this.setInputValid(this.extendModelIds({
                  valid: error.response.data.form.vars.valid,
                  errors: error.response.data.form.vars.errors
                }))
              } else {
                console.warn('validateField request error: ', error.response)
                this.setInputValid(this.extendModelIds({
                  valid: false,
                  errors: ['<b>' + error.response.status + ' ' + error.response.statusText + ':</b> ' + error.response.data['hydra:description']]
                }))
              }
            } else {
              console.warn(error)
            }
            this.validating = false
          }
        }
      }
    }
  },
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
    validating () {
      this.setInputValidating(
        this.extendModelIds({
          validating: this.validating
        })
      )
    }
  },
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
