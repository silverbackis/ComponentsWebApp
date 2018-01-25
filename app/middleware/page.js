const removeParent = (obj) => {
  obj = Object.assign({}, obj)
  delete obj.parent
  return obj
}

export default async function ({ store, route, redirect, error }, cb) {
  if (store.getters.isRouteLoading) {
    console.log('route is already loading')
    cb()
    return
  }
  console.log('load route')
  store.commit('routeLoading')
  let routeData
  try {
    routeData = await store.dispatch('page/FETCH_ROUTE', { route })
  } catch (err) {
    console.warn(err)
    if (err.response && err.response.status) {
      error({statusCode: err.response.status, message: err.response.statusText})
    } else {
      error({statusCode: err.statusCode || 500, message: 'Error fetching from API'})
    }
    cb()
    return
  }
  // Follow all redirects in data tree - to do: ability to set max redirects
  let maxRedirects = 10
  if (maxRedirects) {
    let redirects = 0
    while (
      routeData.redirect !== null &&
      redirects <= maxRedirects
    ) {
      routeData = routeData.redirect
      redirects++
    }
    if (redirects) {
      redirect(routeData.route)
    }
  }
  console.log('passed redirects', routeData)
  let data = [removeParent(routeData.page)]
  routeData = routeData.page
  while (routeData.parent) {
    data.unshift(removeParent(routeData.parent))
    routeData = routeData.parent
  }
  console.log('page data', data)
  await store.commit('page/SET_ROUTE_PAGES', { data })
  await store.dispatch('page/FETCH_PAGES')
  store.commit('routeLoading', false)
  cb()
}
