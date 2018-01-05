import Vue from 'vue'
import axios from 'axios'
const AxiosCancelToken = axios.CancelToken

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
        cancelToken: null,
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
          displayErrors: null,
          cancelTokenSource: null
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
    Vue.set(state.forms[formId].models[inputName], 'value', value)
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
  },
  refreshToken (state, {formId, inputName}) {
    state.forms[formId].models[inputName].cancelTokenSource = AxiosCancelToken.source()
  }
}
