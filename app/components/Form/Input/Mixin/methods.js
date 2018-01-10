import _ from 'lodash'
import { mapMutations, mapActions } from 'vuex'
import axios from 'axios'

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
      submit: 'forms/submit',
      refreshCancelToken: 'forms/refreshCancelToken'
    }),
    async inputBlur () {
      this.displayErrors = true
      await this.beginValidation()
    },
    beginValidation () {
      const localValue = this.child ? this.child.vars.value : this.inputModel
      if (this.lastValidationValue !== localValue) {
        this.lastValidationValue = this.inputModel
        this.validating = true
        if (this.cancelToken) {
          this.cancelToken.cancel(DUPLICATE_CANCEL_MESSAGE)
        }
        if (this.isCheckRadio) {
          return this.validate()
        } else {
          if (this.debounceValidate) {
            this.debounceValidate.cancel()
          }
          this.debounceValidate = _.debounce(() => {
            return this.validate()
          }, 350)
          return this.debounceValidate()
        }
      }
    },
    async validate () {
      this.refreshCancelToken({ formId: this.formId, inputName: this.inputName })
      let postObj = this.inputSubmitData
      try {
        let { data } = await this.$axios.request(
          {
            url: this.action,
            data: postObj,
            method: 'PATCH',
            cancelToken: this.cancelToken.token,
            validateStatus (status) {
              return [ 400, 200, 201 ].indexOf(status) !== -1
            }
          }
        )
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
