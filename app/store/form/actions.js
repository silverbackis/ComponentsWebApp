export const actions = {
  refreshToken ({ commit }, { formId, inputName }) {
    commit('refreshToken', { formId, inputName })
  }
}
