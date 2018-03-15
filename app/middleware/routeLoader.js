export const RouteLoader = async function ({ store: { dispatch }, route, redirect, error }) {
  let routeData
  try {
    routeData = await dispatch('fetchRoute', { route })
  } catch (err) {
    await dispatch('layout/init')
    if (err.response && err.response.status) {
      error({statusCode: err.response.status, message: err.response.statusText})
    } else {
      error({statusCode: err.statusCode || 500, message: 'Error fetching from API'})
    }
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
      return
    }
  }

  await dispatch('initRoute', routeData)
}

export default RouteLoader
