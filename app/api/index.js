const logRequests = !!process.env.DEBUG_API
const requestExpireTime = process.env.NODE_ENV === 'development' ? 0 : (1000 * 15)
let requests = {}
let requestsInfo = {}

const isPreviousRequestExpired = (path) => {
  const now = Date.now()
  if (
    requestsInfo[path] !== undefined &&
    (now - requestsInfo[path].__lastUpdated) <= requestExpireTime
  ) {
    return false
  }

  requestsInfo[path] = {
    __lastUpdated: now
  }
  return true
}

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
  logRequests && console.log(path, '<<expired>>', isPreviousRequestExpired(path))
  if (!isPreviousRequestExpired(path)) {
    return requests[path]
  }
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
        logRequests && console.log(response)
        resolve(response.data)
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
