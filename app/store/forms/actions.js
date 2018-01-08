import { getFormId } from '~/components/Form/_FormId'

export const actions = {
  init ({ commit, state }, form) {
    const formData = form.vars
    const formId = getFormId(formData)
    if (!state.forms[formId]) {
      commit('setForm', { formData })
    }
  },
  initInput ({ commit, state }, { formId, inputVars }) {
    if (!state.forms[formId][inputVars.full_name]) {
      commit('setInput', {
        formId,
        inputData: {
          validating: false,
          displayErrors: false,
          vars: Object.assign(inputVars, {valid: false})
        }
      })
    }
  }
}
