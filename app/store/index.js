export const state = () => ({
  error: false,
  apiUrl: null
})

export const mutations = {
  setError (state, error) {
    if (error.message) {
      error.message = 'API fetch error: ' + error.message
    }
    state.error = error
  },
  setApiUrl (state, apiUrl) {
    state.apiUrl = apiUrl
  }
}

export const getters = {
  getApiUrl: (state) => (path) => {
    return state.apiUrl + path
  }
}

export const actions = {
  async nuxtServerInit ({ dispatch, commit }, { app }) {
    commit('setApiUrl', process.env.API_URL_BROWSER + '/')
    await dispatch('layout/init', { $axios: app.$axios })
  }
}
