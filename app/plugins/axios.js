export default async function ({ req, $axios, store: { getters } }) {
  $axios.interceptors.request.use((config) => {
    const token = process.server ? req.session.authToken : getters.getAuthToken
    if (token) {
      config.headers.Authorization = 'Bearer ' + token
    }
    return config
  })
}
