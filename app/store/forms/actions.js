import { getFormId } from '~/components/Form/_FormId'
import { fetch } from '~/api'

export const actions = {
  init ({ commit, state }, form) {
    const formData = form.vars
    const formId = getFormId(formData)
    if (!state.forms[formId]) {
      commit('setForm', {
        formData: {
          vars: formData,
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
            value
          }),
          valid: false,
          children
        }
      })
    }
  },
  submit (ctx, { path, data, cancelToken, method }) {
    return fetch({
      $axios: this.$axios,
      path,
      data,
      cancelToken,
      method,
      validateStatus (status) {
        return [ 400, 200, 201 ].indexOf(status) !== -1
      }
    })
  }
}
