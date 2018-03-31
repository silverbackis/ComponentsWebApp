import Vue from 'vue'
import { fetch } from '../api/index'
import { flattenComponentData } from './component'

const DEFAULT = '/layouts/default'

export const state = () => ({
  data: {},
  current: '',
  default: ''
})

export const getters = {
  getLayout: state => state.data[state.current],
  getDefault: state => state.default
}

export const mutations = {
  setLayout (state, { data, isDefault }) {
    Vue.set(state.data, data['@id'], data)
    if (isDefault || data.default) {
      state.default = data['@id']
    }
    state.current = data['@id']
  }
}

export const actions = {
  async init ({ commit, state, dispatch }, id) {
    id = (id || DEFAULT)
    try {
      let data = await fetch({ path: id, $axios: this.$axios })
      commit('setLayout', { data, isDefault: DEFAULT === id })

      if (data.navBar) {
        // map the data to individual components
        let components = flattenComponentData([{ component: data.navBar }])
        Object.keys(components).forEach((componentId) => {
          commit('component/setComponent', components[componentId], { root: true })
        })
      }
    } catch (err) {
      console.warn('Layout init error', id, err)
      commit('setError', { source: 'store/modules/layout.js', statusCode: err.statusCode, message: err.message || 'Failed to load layout' }, {root: true})
    }
  }
}
