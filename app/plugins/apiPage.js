import Vue from 'vue'

const logRequests = !!process.env.DEBUG_API

const ApiPage = {
  install () {
    if (Vue.__apipage_installed__) {
      return
    }
    Vue.__apipage_installed__ = true
    if (!Vue.prototype.hasOwnProperty('$getPage')) {
      Object.defineProperty(Vue.prototype, '$getPage', {
        get () {
          return this.$root.$options.$getPage
        }
      })
    }
  }
}
Vue.use(ApiPage)

let requests = {}

function fetch ({ app, path }, cb) {
  logRequests && console.log(`fetching ${path}...`)
  if (!requests[path]) {
    requests[path] = new Promise((resolve, reject) => {
      app.$axios
        .get(path)
        .then(async ({ data }) => {
          requests[path] = undefined
          if (cb) {
            await cb(data)
          }
          resolve(data)
        })
        .catch((err) => {
          reject(err)
        })
    })
  }
  return requests[path]
}

async function fetchPage ({ error, app }, { path }) {
  try {
    return fetch({ app, path })
  } catch (err) {
    error({ statusCode: err.statusCode, message: 'Error (' + path + '): ' + err.message })
  }
}

async function fetchRoutes ({ error, store, app }, { path }) {
  try {
    path = '/routes/' + path
    await fetch({ app, path }, async (data) => {
      data = data['hydra:member']
      await store.commit('page/SET_DEPTH_IDS', { data })
      await store.dispatch('page/FETCH_PAGES', {
        ids: store.state.page.depthIds
      })
    })
  } catch (err) {
    error({ statusCode: err.statusCode, message: 'Error (' + path + '): ' + err.message })
  }
}

export default (ctx, inject) => {
  const ROUTES = (payload) => {
    return fetchRoutes(ctx, payload)
  }
  ctx.$getRoutePages = ROUTES
  inject('getRoutePages', ROUTES)

  const PAGE = (payload) => {
    return fetchPage(ctx, payload)
  }
  ctx.$getPage = PAGE
  inject('getPage', PAGE)
}
