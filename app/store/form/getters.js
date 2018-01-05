import _ from 'lodash'

export const getters = {
  /*
   Form data
   */
  getForm: (state) => (formId) => {
    return !state.forms[formId] ? null : state.forms[formId]
  },
  getFormAction: (state, getters) => (formId) => {
    let form = getters.getForm(formId)
    return !form ? null : form.action
  },
  getFormValid: (state, getters) => (formId) => {
    let form = getters.getForm(formId)
    return !form ? null : form.valid
  },
  getFormErrors: (state, getters) => (formId) => {
    let form = getters.getForm(formId)
    return !form ? null : form.errors
  },
  getFormModels: (state, getters) => (formId) => {
    let form = getters.getForm(formId)
    return !form ? null : form.models
  },
  getFormAxiosCancelToken: (state, getters) => (formId) => {
    let form = getters.getForm(formId)
    return !form ? null : form.cancelToken
  },
  getFormAxiosCancelFn: (state, getters) => (formId) => {
    let form = getters.getForm(formId)
    return !form ? null : form.axiosCancel
  },
  getFormSubmitting: (state, getters) => (formId) => {
    let form = getters.getForm(formId)
    return !form ? null : form.submitting
  },
  /*
   Model data
   */
  getInput: (state, getters) => (formId, inputName) => {
    let form = getters.getForm(formId)
    return !form ? null : form.models[inputName]
  },
  getInputValue: (state, getters) => (formId, inputName) => {
    let model = getters.getInput(formId, inputName)
    return !model ? null : model.value
  },
  getInputValid: (state, getters) => (formId, inputName) => {
    let model = getters.getInput(formId, inputName)
    return !model ? null : model.valid
  },
  getInputValidating: (state, getters) => (formId, inputName) => {
    let model = getters.getInput(formId, inputName)
    return !model ? null : !!model.validating
  },
  getInputErrors: (state, getters) => (formId, inputName) => {
    let model = getters.getInput(formId, inputName)
    return !model ? null : model.errors
  },
  getInputDisplayErrors: (store, getters) => (formId, inputName) => {
    let model = getters.getInput(formId, inputName)
    return !model ? null : model.displayErrors
  },
  getInputCurrentErrors: (store, getters) => (formId, inputName) => {
    let model = getters.getInput(formId, inputName)
    return !model ? null : (model.displayErrors && !model.valid) ? model.errors : null
  },
  getInputAxiosCancelToken: (state, getters) => (formId, inputName) => {
    let model = getters.getInput(formId, inputName)
    return !model ? null : model.cancelTokenSource
  },

  /*
   Submit data
   */
  getFormSubmitData: (state, getters) => (formId) => {
    let models = getters.getFormModels(formId)
    let submitObj = {}
    for (let inputName of Object.keys(models)) {
      let modelData = getters.getInputStructuredData(inputName, models[inputName].value)
      _.merge(submitObj, modelData)
    }
    return submitObj
  },
  getInputSubmitData: (state, getters) => (formId, inputName) => {
    let model = getters.getInput(formId, inputName)
    if (!model) {
      return {}
    }
    return getters.getInputStructuredData(inputName, model.value)
  },

  /*
   Utils
   */
  getInputStructuredData: () => (name, value) => {
    // Split name into parts when using square brackets - e.g. contact[name] = ["contact", "name"]
    if (value === undefined) {
      return {}
    }
    let searchResult = name.split(/\[(.+)\]/).filter(String)
    let submitObj = {}
    _.set(submitObj, searchResult, value)
    return submitObj
  }
}
