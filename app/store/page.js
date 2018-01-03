import Vue from 'vue'
import { compile } from '~/.nuxt/utils'

export const state = () => ({
  depthIds: [],
  pages: {}
})

export const getters = {
  getPageByDepth: (state) => (depth) => {
    return state.pages[state.depthIds[depth]]
  }
}

export const mutations = {
  SET_DEPTH_IDS: (state, { data }) => {
    if (Array.isArray(data)) {
      state.depthIds = []
      data.forEach((val) => {
        state.depthIds.push(val['@id'])
      })
    }
  },
  SET_PAGE: (state, { path, data }) => {
    Vue.set(state.pages, path, data)
  }
}

export const actions = {
  async FETCH_DEPTH_DATA ({ state, commit, dispatch, getters }, { depth, route }) {
    let path = compile(route.path)(route.params) || '/'
    await this.$getRoutePages({ path: path })
  },

  async FETCH_PAGES ({ state, dispatch }, { ids }) {
    // on the client, the store itself serves as a cache.
    // only fetch items that we do not already have, or has expired (15 seconds)
    const now = Date.now()
    ids = ids.filter(id => {
      const item = state.pages[id]
      if (!item) {
        return true
      }
      return (now - item.__lastUpdated) > (1000 * 15)
    })
    if (ids.length) {
      await dispatch('FETCH_ITEMS', ids)
    }
  },

  FETCH_ITEMS: ({ dispatch }, ids) => {
    return Promise.all(ids.map(id => dispatch('FETCH_PAGE', id)))
  },

  async FETCH_PAGE ({ commit }, path) {
    let data = await this.$getPage({ path })
    data.__lastUpdated = Date.now()
    return commit('SET_PAGE', { path, data })
  }
}
