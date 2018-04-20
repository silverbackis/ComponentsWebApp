const logRequests = !!process.env.DEBUG_API
let requests = {}

export const fetch = ({ path, $axios, method, data, cancelToken, validateStatus }) => {
  if (!method) {
    method = 'GET'
  }
  if (!validateStatus) {
    validateStatus = (status) => {
      return status >= 200 && status < 300
    }
  }
  logRequests && console.log(`fetching ${path}...`)
  requests[path] = new Promise((resolve, reject) => {
    $axios
      .request({
        url: path,
        method,
        data,
        cancelToken,
        validateStatus
      })
      .then((response) => {
        logRequests && console.log('api/index.js', response)
        resolve(response)
      })
      .catch((err) => {
        reject(err)
      })
  })
  return requests[path]
}

export function fetchAll ({ paths, $axios }) {
  return Promise.all(paths.map(path => fetch({ path, $axios })))
}

export function fetchRoute ({ path, $axios }) {
  return fetch({ path: `/routes/${path}`, $axios })
}
