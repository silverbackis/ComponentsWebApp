import { getFormId } from '~/components/Form/_FormId'
import axios from 'axios'
const AxiosCancelToken = axios.CancelToken

export const actions = {
  init ({ commit, state }, form) {
    const formData = form.vars
    const formId = getFormId(formData)
    if (!state.forms[formId]) {
      commit('setForm', {
        formData: {
          vars: Object.assign({}, formData, { valid: false }),
          children: {},
          cancelToken: null,
          submitting: false
        }
      })
    }
  },
  initInput ({ commit, state }, { formId, inputVars, children }) {
    if (!state.forms[formId].children[inputVars.full_name]) {
      let value = inputVars.multiple ? [] : (inputVars.block_prefixes[1] === 'checkbox' ? inputVars.checked : inputVars.value)

      commit('setInput', {
        formId,
        inputData: {
          validating: false,
          displayErrors: false,
          debounceValidate: null,
          cancelToken: null,
          lastValidationValue: null,
          vars: Object.assign({}, inputVars, {
            valid: false,
            errors: [],
            value
          }),
          children
        }
      })
    }
  },
  refreshCancelToken ({ commit }, { formId, inputName }) {
    let cancelToken = AxiosCancelToken.source()
    if (inputName) {
      commit('setInputCancelToken', { formId, inputName, cancelToken })
    } else {
      commit('setFormCancelToken', { formId, cancelToken })
    }
  }
}
