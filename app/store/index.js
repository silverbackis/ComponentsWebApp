import _ from 'lodash'
import { fetchRoute } from '../api'
import { compile } from '~/.nuxt/utils'

export const state = () => ({
  error: false,
  apiUrl: null,
  content: null
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
  },
  setContent (state, content) {
    state.content = content
  }
}

export const getters = {
  getApiUrl: (state) => (path) => {
    return state.apiUrl + _.trimStart(path, '/')
  },
  getContent: state => (depth) => {
    return state.content[depth] || false
  }
}

export const actions = {
  nuxtServerInit ({ dispatch, commit }) {
    commit('setApiUrl', process.env.API_URL_BROWSER + '/')
  },
  async fetchRoute (ctx, { route }) {
    let path = compile(route.path)(route.params) || '/'
    return fetchRoute({ path, $axios: this.$axios })
  },
  async initRoute ({ commit, dispatch }, { content }) {
    const withoutParent = (obj) => {
      obj = Object.assign({}, obj)
      delete obj.parent
      return obj
    }

    let contentArray = [withoutParent(content)]
    while (content.parent) {
      contentArray.unshift(withoutParent(content.parent))
      content = content.parent
    }
    commit('setContent', contentArray)
    let pageComponentInits = []
    contentArray.forEach((page) => {
      pageComponentInits.push(dispatch('component/init', page.componentLocations))
    })

    if (content.layout.navBar) {
      let mockLocations = [{ component: content.layout.navBar }]
      pageComponentInits.push(dispatch('component/init', mockLocations))
    }

    await Promise.all(
      [
        dispatch('layout/init', content.layout['@id']),
        ...pageComponentInits
      ]
    )
  }
}
