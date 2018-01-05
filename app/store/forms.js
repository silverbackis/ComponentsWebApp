import Vue from 'vue'
import axios from 'axios'
import _ from 'lodash'

const AxiosCancelToken = axios.CancelToken

export const state = () => ({
  forms: {}
})

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
    return !form ? null : form.axiosCancelToken
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
    return !model ? null : model.validating
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

export const mutations = {
  /*
   Form mutations / methods
   */
  initForm (state, {formId, models, action}) {
    if (state.forms[formId] === undefined) {
      Vue.set(state.forms, formId, {
        models: {},
        valid: false,
        errors: null,
        axiosCancel: null,
        action: action,
        submitting: false
      })
      Vue.set(state.forms[formId], 'axiosCancelToken', new AxiosCancelToken((c) => {
        state.forms[formId].axiosCancel = c
      }))
      for (let inputName of Object.keys(models)) {
        Vue.set(state.forms[formId].models, models[inputName].vars.full_name, {
          formId: formId,
          label: null,
          value: null,
          validating: null,
          valid: null,
          errors: null,
          displayErrors: null
        })
      }
    }
  },
  setFormValid (state, {formId, valid, errors, models}) {
    let form = state.forms[formId]
    let storeModels = state.forms[formId].models
    form.valid = valid
    form.errors = errors ? errors.errors : false
    for (let inputName of Object.keys(models)) {
      let modelVars = models[inputName].vars
      let storeModel = storeModels[modelVars.full_name]
      storeModel.errors = modelVars.errors && modelVars.errors.errors.length ? modelVars.errors.errors : false
      storeModel.valid = modelVars.valid && !storeModel.errors
      if (!storeModel.valid) {
        storeModel.displayErrors = true
      }
    }
  },
  cancelForm (state, formId) {
    if (!state.forms[formId] || state.forms[formId].axiosCancel !== Function) {
      return false
    }
    state.forms[formId].axiosCancel()
    Vue.set(state.forms[formId], 'axiosCancelToken', new AxiosCancelToken((c) => {
      state.forms[formId].axiosCancel = c
    }))
    return true
  },
  destroyForm (state, formId) {
    Vue.delete(state.forms, formId)
  },
  setFormSubmitting (state, { formId, submitting }) {
    let form = state.forms[formId]
    if (form) {
      form.submitting = submitting
    }
  },
  /*
    Model mutations / methods
   */
  initInput (state, {formId, inputName, label, validating, displayErrors}) {
    state.forms[formId].models[inputName].label = label
    state.forms[formId].models[inputName].validating = validating
    state.forms[formId].models[inputName].displayErrors = displayErrors
  },
  setInputLabel (state, {formId, model, label}) {
    state.forms[formId].models[model].label = label
  },
  setInputValue (state, {formId, inputName, value}) {
    state.forms[formId].models[inputName].value = value
  },
  setInputValidating (state, {formId, inputName, validating}) {
    state.forms[formId].models[inputName].validating = validating
  },
  setInputValid (state, {formId, inputName, valid, errors}) {
    state.forms[formId].models[inputName].valid = valid
    state.forms[formId].models[inputName].errors = errors
  },
  setInputDisplayErrors (state, {formId, inputName, displayErrors}) {
    state.forms[formId].models[inputName].displayErrors = displayErrors
  }
}
