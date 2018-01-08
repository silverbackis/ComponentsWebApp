import Vue from 'vue'
import { getFormId } from '~/components/Form/_FormId'

export const mutations = {
  setForm (state, { formData }) {
    let formId = getFormId(formData)
    Vue.set(
      state.forms,
      formId,
      {
        vars: formData,
        children: {},
        cancelToken: null,
        submitting: false
      }
    )
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
    let inputVars = state.forms[formId].children[inputName].vars
    inputVars.valid = valid
    inputVars.errors = errors
  },
  setInputDisplayErrors (state, { formId, inputName, displayErrors }) {
    if (typeof displayErrors === typeof true) {
      Vue.set(state.forms[formId].children[inputName], 'displayErrors', displayErrors)
    } else {
      console.warn('Failed to set displayErrors - not a boolean', displayErrors, formId, inputName)
    }
  },
  setInputValidating (state, {formId, inputName, validating}) {
    Vue.set(state.forms[formId].children[inputName], 'validating', validating)
  }
}
