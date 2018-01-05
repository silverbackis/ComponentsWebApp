import { mapMutations } from 'vuex'
import axios from 'axios'
const DUPLICATE_CANCEL_MESSAGE = 'duplicate'
export default {
  methods: {
    ...mapMutations({
      initInput: 'forms/initInput',
      setInputValue: 'forms/setInputValue',
      setInputValidating: 'forms/setInputValidating',
      setInputValid: 'forms/setInputValid',
      setInputDisplayErrors: 'forms/setInputDisplayErrors'
    }),
    setValidating (validating) {
      this.setInputValidating(
        this.extendModelIds({
          validating
        })
      )
    },
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
        if (this.isRadio || this.lastValidationValue !== this.modelValue) {
          let obj = this.getInputSubmitData(this.formId, this.vars.full_name)
          this.lastValidationValue = this.modelValue
          if (this.cancelToken) {
            await this.cancelToken.cancel(DUPLICATE_CANCEL_MESSAGE)
          }
          await this.$store.dispatch('forms/refreshToken', { formId: this.formId, inputName: this.vars.full_name })

          this.setValidating(true)
          try {
            let { data } = await this.$axios.request({
              url: '/forms/submit/9',
              data: obj,
              method: 'PATCH',
              validateStatus: function (status) {
                return [ 406, 200 ].indexOf(status) !== -1 // default
              },
              cancelToken: this.cancelToken.token
            })
            this.setInputValid(this.extendModelIds({
              valid: data.form.vars.valid,
              errors: data.form.vars.errors
            }))
            // turn off validating because may be a radio and the new request may not be assigned to this component
            this.setValidating(false)
          } catch (error) {
            if (error.message === DUPLICATE_CANCEL_MESSAGE) {
              console.log('previous input validation request cancelled')
            } else {
              if (axios.isCancel(error)) {
                console.warn(error)
              } else {
                console.warn('validateField request error: ', error.response)
                this.setInputValid(this.extendModelIds({
                  valid: false,
                  errors: ['<b>' + error.response.status + ' ' + error.response.statusText + ':</b> ' + error.response.data['hydra:description']]
                }))
              }
              // turn off validating because may be a radio and the new request may not be assigned to this component
              this.setValidating(false)
            }
          }
        }
      }
    }
  }
}
