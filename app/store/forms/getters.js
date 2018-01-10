import _ from 'lodash'

export const getters = {
  getForm: (state) => (formId) => {
    return !state.forms[formId] ? null : state.forms[formId]
  },
  getInput: (state, getters) => (formId, inputName) => {
    let form = getters.getForm(formId)
    return !form ? null : form.children[inputName]
  },
  getFormSubmitData: (state, getters) => (formId) => {
    let form = getters.getForm(formId)
    if (!form) {
      return {}
    }

    let keys = _.keys(form.children)
    let x = keys.length
    let inputName
    let submit = {}
    while (x--) {
      inputName = form.children[keys[x]].vars.full_name
      submit = _.merge(
        submit,
        getters.getInputSubmitData({ formId, inputName })
      )
    }
    return submit
  },
  getInputSubmitData: (state) => ({ formId, inputName }) => {
    let model = state.forms[formId].children[inputName]
    let value = model.vars.value
    if (value === undefined) {
      return {}
    }
    // Remove '[]' from end of name if it exists
    // Split name into parts when using square brackets - e.g. contact[name] = ["contact", "name"]
    let searchResult = inputName.replace(/\[\]$/, '').split(/\[(.+)\]/).filter(String)
    let submitObj = {}
    _.set(submitObj, searchResult, value)
    return submitObj
  }
}
