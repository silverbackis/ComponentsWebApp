import _ from 'lodash'
import { fetchRoute } from '../api'
import { compile } from '~/.nuxt/utils'

export const strict = false

export const state = () => ({
  error: false,
  apiUrl: null,
  content: null,
  authUser: null
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
  },
  setAuthUser (state, user) {
    state.authUser = user
  },
  hasRole: (state) => (role) => {
    return !state.authUser ? false : (state.authUser.roles ? state.authUser.roles.indexOf(role) !== -1 : false)
  }
}

export const getters = {
  getApiUrl: (state) => (path) => {
    return state.apiUrl + _.trimStart(path, '/')
  },
  getContent: state => (depth) => {
    return state.content[depth] || false
  },
  getAuthUser: state => state.authUser
}

export const actions = {
  nuxtServerInit ({ dispatch, commit }) {
    commit('setApiUrl', process.env.API_URL_BROWSER + '/')
  },
  async fetchRoute (ctx, { route }) {
    let path = compile(route.path)(route.params) || '/'
    return fetchRoute({ path, $axios: this.$axios })
  },
  async initRoute ({ commit, dispatch, rootGetters }, { content }) {
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
    let promises = []
    contentArray.forEach((page) => {
      if (page.componentLocations.length) {
        promises.push(dispatch('component/initPage', page))
      }
    })

    if (!content.layout) {
      console.warn('No layout set and no default layout found')
    } else {
      if (content.layout) {
        promises.push(dispatch('layout/init', content.layout['@id']))
      }
    }

    await Promise.all(promises)
  }
}
