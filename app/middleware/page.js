export default async function ({ store, route, redirect, error }, cb) {
  let routeData
  try {
    routeData = await store.dispatch('page/FETCH_ROUTE', { route })
  } catch (err) {
    if (err.response && err.response.status) {
      error({ statusCode: err.response.status, message: err.response.statusText })
    } else {
      console.warn(err)
      error({ statusCode: err.statusCode || 500, message: 'Error fetching from API' })
    }
    cb()
    return
  }
  if (routeData.redirect) {
    return redirect(routeData.redirect.route)
  }
  let data = routeData['hydra:member']
  await store.commit('page/SET_ROUTE_PAGES', { data })
  await store.dispatch('page/FETCH_PAGES')
  cb()
}
