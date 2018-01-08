import Vue from 'vue'
import { getFormId } from '~/components/Form/_FormId'

export const mutations = {
  setForm (state, { formData, models }) {
    let FormId = getFormId(formData)
    Vue.set(
      state.forms,
      FormId,
      {
        vars: formData,
        children: models,
        cancelToken: null,
        submitting: false
      }
    )
  }
}
