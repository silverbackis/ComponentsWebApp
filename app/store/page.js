import Vue from 'vue'
import { compile } from '~/.nuxt/utils'
import { fetchRoute, fetchPageIds } from '../api'

export const state = () => ({
  routePages: [],
  pages: {}
})

export const getters = {
  getPageByDepth: (state) => (depth) => {
    return state.pages[state.routePages[depth]]
  }
}

export const mutations = {
  SET_ROUTE_PAGES: (state, { data }) => {
    if (Array.isArray(data)) {
      state.routePages = []
      data.forEach((val) => {
        state.routePages.push(val['id'])
      })
    }
    console.log('route pages set')
  },
  SET_PAGE: (state, { id, data }) => {
    Vue.set(state.pages, id, data)
  }
}

export const actions = {
  async FETCH_ROUTE ({ commit }, { route }) {
    let path = compile(route.path)(route.params) || '/'
    return fetchRoute({ path, $axios: this.$axios })
  },

  async FETCH_PAGES ({ state, commit }) {
    console.log('checking pages to fetch from pages for route', state.routePages)
    // Filter to only load pages that haven't been loaded within last 15 seconds
    const now = Date.now()
    let ids = state.routePages.filter(id => {
      const item = state.pages[id]
      if (!item) {
        return true
      }
      return (now - item.__lastUpdated) > (1000 * 15)
    })
    console.log('fetching ids', ids)
    // If we still need to reload/load pages now we can do it
    if (ids.length) {
      let data = await fetchPageIds({ ids, $axios: this.$axios })
      console.log('pages fetched')
      data.forEach((pageData) => {
        pageData.__lastUpdated = now
        commit('SET_PAGE', { id: pageData.id, data: pageData })
        console.log('set page data', pageData)
      })
    }
  }
}
