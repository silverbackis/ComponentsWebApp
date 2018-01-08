export const getters = {
  getForm: (state) => (formId) => {
    return !state.forms[formId] ? null : state.forms[formId]
  },
  getInput: (state, getters) => (formId, inputName) => {
    let form = getters.getForm(formId)
    return !form ? null : form.children[inputName]
  }
}
