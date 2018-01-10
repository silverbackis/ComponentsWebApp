import Vue from 'vue'
import { getFormId } from '~/components/Form/_FormId'

export const mutations = {
  setForm (state, { formData }) {
    let formId = getFormId(formData.vars)
    Vue.set(
      state.forms,
      formId,
      formData
    )
  },
  setFormValidationResult (state, {formId, valid, errors}) {
    Vue.set(state.forms[formId].vars, 'valid', valid)
    Vue.set(state.forms[formId].vars, 'errors', errors)
  },
  setInput (state, { formId, inputData }) {
    Vue.set(
      state.forms[formId].children,
      inputData.vars.full_name,
      inputData
    )
  },
  setInputValue (state, { formId, inputName, value }) {
    Vue.set(state.forms[formId].children[inputName].vars, 'value', value)
  },
  setInputValidationResult (state, {formId, inputName, valid, errors}) {
    Vue.set(state.forms[formId].children[inputName].vars, 'valid', valid)
    Vue.set(state.forms[formId].children[inputName].vars, 'errors', errors)
  },
  setInputDisplayErrors (state, { formId, inputName, displayErrors }) {
    Vue.set(state.forms[formId].children[inputName], 'displayErrors', displayErrors)
  },
  setInputValidating (state, {formId, inputName, validating}) {
    Vue.set(state.forms[formId].children[inputName], 'validating', validating)
  },
  setInputDebounceValidate (state, { formId, inputName, debounce }) {
    Vue.set(state.forms[formId].children[inputName], 'debounceValidate', debounce)
  },
  setInputCancelToken (state, { formId, inputName, cancelToken }) {
    Vue.set(state.forms[formId].children[inputName], 'cancelToken', cancelToken)
  },
  setInputLastValidationValue (state, { formId, inputName, value }) {
    Vue.set(state.forms[formId].children[inputName], 'lastValidationValue', value)
  },
  setFormSubmitting (state, { formId, submitting }) {
    Vue.set(state.forms[formId], 'submitting', submitting)
  },
  setFormCancelToken (state, { formId, cancelToken }) {
    Vue.set(state.forms[formId], 'cancelToken', cancelToken)
  }
}
