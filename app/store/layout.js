import Vue from 'vue'
import { fetch } from '../api/index'

export const state = () => ({
  data: {},
  current: null
})

export const getters = {
  getLayout (state) {
    return state.data[state.current]
  }
}

export const mutations = {
  setLayout (state, data) {
    state.current = data['@id']
    Vue.set(state.data, data['@id'], data)
  }
}

export const actions = {
  async init ({ commit, state, dispatch }, id) {
    id = (id || '/layouts/default')
    try {
      let data = await fetch({ path: id, $axios: this.$axios })
      commit('setLayout', data)
    } catch (err) {
      console.warn('Layout init error', id, err)
      commit('setError', { source: 'store/modules/layout.js', statusCode: err.statusCode, message: err.message || 'Failed to load layout' }, {root: true})
    }
  }
}
