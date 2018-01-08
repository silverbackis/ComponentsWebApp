import { getFormId } from '~/components/Form/_FormId'

export const actions = {
  init ({ commit, state }, form) {
    const formData = form.vars
    const formId = getFormId(formData)
    if (!state.forms[formId]) {
      // Init all form models
      let models = {}
      // Fastest loop
      let index = form.children.length
      while (index--) {
        let inputVars = form.children[index].vars
        models[inputVars.full_name] = {
          validating: false,
          vars: Object.assign(inputVars, { valid: false })
        }
      }
      // Commit the data
      commit('setForm', { formData, models })
    }
  }
}
