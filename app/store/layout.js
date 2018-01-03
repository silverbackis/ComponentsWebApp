export const state = () => ({
  data: null
})

export const mutations = {
  setData (state, data) {
    state.data = data
  }
}

export const actions = {
  async init ({ commit }, { $axios }) {
    try {
      let { data } = await $axios.get('layouts/default')
      commit('setData', data)
    } catch (err) {
      commit('setError', { statusCode: err.statusCode, message: err.message || 'Failed to load menu' }, {root: true})
    }
  }
}
