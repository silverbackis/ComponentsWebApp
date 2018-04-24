import CookiesToHeaders from '../api/CookiesToHeaders'
import RefreshToken from '../api/RefreshToken'

const logging = process.env.NODE_ENV === 'development'

export default async function ({ req, res, $axios, store: { getters, commit } }) {
  const axiosRefreshToken = async () => {
    if (process.server) {
      try {
        let result = await RefreshToken(req, res, false)
        commit('setAuthToken', result)
        logging && console.log('Refresh result - token', result)
      } catch (refreshError) {
        commit('setAuthToken', null)
        logging && console.error('refreshError', refreshError)
      }
    } else {
      // Request to the express back-end
      try {
        let { data } = await $axios.post(
          'refresh_token',
          { _action: '/token/refresh' },
          { baseURL: null, refreshTokenRequest: true }
        )
        commit('setAuthToken', data.token)
      } catch (refreshError) {
        logging && console.warn('refreshError', refreshError)
        // It turns out we are not authorized - but the page may not even need authorization...
        commit('setAuthToken', null)
        if (refreshError.statusCode >= 500 && refreshError.statusCode < 600) {
          return Promise.reject(refreshError)
        }
      }
    }
  }

  // Always send through Authorization header if the authToken is available
  $axios.interceptors.request.use(async (config) => {
    const authToken = getters.getAuthToken
    const authDiff = authToken ? getters.getAuthUser.exp - (Date.now() / 1000) : 10
    // Expired or very close to expired (10 secs)
    if (authDiff < 10 && !config.refreshTokenRequest) {
      logging && console.log('Request intercepted - auth token expiry detected')
      await axiosRefreshToken()
    }

    const token = process.server ? req.session.authToken : getters.getAuthToken
    if (token) {
      config.headers.Authorization = 'Bearer ' + token
    }
    if (process.server) {
      let headers = CookiesToHeaders(req.cookies)
      config.headers = Object.assign(config.headers, headers)
    }
    return config
  })

  // When we get responses, we should check for an unauthorized response
  // If unauthorized, we should see if we have a token, and try refreshing it if we do
  // If a refresh is successful, retry the original request
  // If a refresh is unsuccessful, return the original unauthorized response
  // Commented out because we know if token has expired - it should really be as simple as that for refreshing
  // $axios.interceptors.response.use(
  //   null,
  //   async (error) => {
  //     logging && console.error('axios.js response interceptor - error', error.statusCode, error.response.config)
  //     if (error.statusCode !== 403 || error.response.config.refreshed === true) {
  //       return Promise.reject(error)
  //     }
  //     logging && console.log('Attempting to refresh...')
  //     await axiosRefreshToken()
  //     error.response.config.refreshTokenRequest = false
  //     error.response.config.refreshed = true
  //     return $axios(error.response.config)
  //   }
  // )
}
