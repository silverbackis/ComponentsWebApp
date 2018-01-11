export default async function ({ store, route, redirect }, cb) {
  console.log('middleware - fetch a route', route)
  let routeData = await store.dispatch('page/FETCH_ROUTE', { route })
  if (routeData.redirect) {
    return redirect(routeData.redirect.route)
  }
  let data = routeData['hydra:member']
  console.log('final route loaded', data)
  await store.commit('page/SET_ROUTE_PAGES', { data })
  await store.dispatch('page/FETCH_PAGES')
  cb()
}
