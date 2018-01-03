export const state = () => ({
  error: false
})

export const mutations = {
  setError (state, error) {
    if (error.message) {
      error.message = 'API fetch error: ' + error.message
    }
    state.error = error
  }
}

export const actions = {
  async nuxtServerInit ({ dispatch }, { app }) {
    await dispatch('layout/init', { $axios: app.$axios })
  }
}
