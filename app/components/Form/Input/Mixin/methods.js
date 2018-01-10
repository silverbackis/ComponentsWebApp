import _ from 'lodash'
import { mapMutations, mapActions } from 'vuex'
import axios from 'axios'
const AxiosCancelToken = axios.CancelToken

const DUPLICATE_CANCEL_MESSAGE = 'duplicate'

export default {
  methods: {
    ...mapMutations({
      setInputValue: 'forms/setInputValue',
      setInputValidating: 'forms/setInputValidating',
      setInputValidationResult: 'forms/setInputValidationResult',
      setInputDebounceValidate: 'forms/setInputDebounceValidate',
      setInputCancelToken: 'forms/setInputCancelToken',
      setInputLastValidationValue: 'forms/setInputLastValidationValue'
    }),
    ...mapActions({
      submit: 'forms/submit'
    }),
    inputBlur () {
      this.displayErrors = true
      this.beginValidation()
    },
    beginValidation () {
      const localValue = this.child ? this.child.vars.value : this.inputModel
      if (this.lastValidationValue !== localValue) {
        this.lastValidationValue = this.inputModel
        this.validating = true
        if (this.isCheckRadio) {
          this.validate()
        } else {
          if (this.debounceValidate) {
            this.debounceValidate.cancel()
          }
          this.debounceValidate = _.debounce(() => {
            this.validate()
          }, 350)
          return this.debounceValidate()
        }
      }
    },
    async validate () {
      if (this.cancelToken) {
        this.cancelToken.cancel(DUPLICATE_CANCEL_MESSAGE)
      }
      this.cancelToken = AxiosCancelToken.source()
      let postObj = await this.inputSubmitData
      try {
        let data = await this.submit({
          path: this.action,
          data: postObj,
          method: 'PATCH',
          cancelToken: this.cancelToken.token
        })
        const VARS = data.form.vars
        this.setInputValidationResult(this.extendInputId({
          valid: VARS.valid,
          errors: VARS.errors
        }))
        this.validating = false
      } catch (error) {
        this.validateError(error)
      }
    },
    validateError (error) {
      if (error.message === DUPLICATE_CANCEL_MESSAGE) {
        console.log('previous input validation request cancelled')
      } else {
        this.validating = false
        if (axios.isCancel(error)) {
          console.warn(error)
        } else if (error.response) {
          console.warn('validate request error: ', error.response)
          this.setInputValidationResult(this.extendInputId({
            valid: false,
            errors: [
              '<b>' + error.response.status + ' ' + error.response.statusText + ':</b> ' +
              error.response.data['hydra:description']
            ]
          }))
        } else {
          console.warn('validate unknown error: ', error)
        }
      }
    }
  }
}
