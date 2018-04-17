import cookies from '~/server/api/cookies'

export const RouteLoader = async function ({ store: { dispatch }, route, redirect, error, res }) {
  let routeData, response
  try {
    response = await dispatch('fetchRoute', { route })
    routeData = response.data
  } catch (err) {
    response = await dispatch('layout/init')
    if (process.server) {
      cookies.setCookies(res, response)
    }

    if (err.response && err.response.status) {
      error({statusCode: err.response.status, message: err.response.statusText})
    } else {
      error({statusCode: err.statusCode || 500, message: 'Error fetching from API'})
      console.warn(err)
    }
    return
  }
  if (process.server) {
    cookies.setCookies(res, response)
  }

  if (!routeData) {
    console.warn(routeData)
    error({statusCode: 500, message: 'Error fetching from API - No Route Data'})
    return
  }
  if (typeof routeData !== 'object') {
    console.warn(routeData)
    error({statusCode: 500, message: 'API returned invalid JSON'})
    return
  }

  // Follow all redirects in data tree - to do: ability to set max redirects
  let maxRedirects = 10
  if (maxRedirects) {
    let redirects = 0
    while (
      routeData.redirect &&
      redirects <= maxRedirects
    ) {
      routeData = routeData.redirect
      redirects++
    }
    if (redirects) {
      redirect(routeData.route)
    }
  }

  await dispatch('initRoute', routeData)
}

export default RouteLoader
