import { mapMutations } from 'vuex'
import axios from 'axios'

export default {
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
        if (this.vars.block_prefixes[2] === 'radio' || this.lastValidationValue !== this.modelValue) {
          let obj = this.getInputSubmitData(this.formId, this.vars.full_name)
          this.lastValidationValue = this.modelValue
          console.log('validate', this.modelValue)
          console.log(this.formId, this.getFormAxiosCancelToken(this.formId))
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
  }
}
