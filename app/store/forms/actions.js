import _ from 'lodash'
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
          vars: Object.assign({}, inputVars, {valid: false})
        }
      })
    }
  },
  inputSubmitData ({ state }, { formId, inputName }) {
    let model = state.forms[formId].children[inputName]
    let value = model.vars.value
    if (value === undefined) {
      return {}
    }
    // Split name into parts when using square brackets - e.g. contact[name] = ["contact", "name"]
    let searchResult = inputName.split(/\[(.+)\]/).filter(String)
    let submitObj = {}
    _.set(submitObj, searchResult, value)
    return submitObj
  }
}
