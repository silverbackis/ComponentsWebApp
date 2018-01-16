const logRequests = !!process.env.DEBUG_API
let requests = {}

function fetch ({ path, $axios, method, data, cancelToken, validateStatus }) {
  if (!method) {
    method = 'GET'
  }
  logRequests && console.log(`fetching ${path}...`)
  if (!requests[path]) {
    requests[path] = new Promise((resolve, reject) => {
      $axios
        .request({
          url: path,
          method,
          data,
          cancelToken,
          validateStatus
        })
        .then(({ data }) => {
          requests[path] = undefined
          resolve(data)
        })
        .catch((err) => {
          reject(err)
        })
    })
  }
  return requests[path]
}

export function fetchPage ({ path, $axios }) {
  return fetch({ path: `/pages/${path}`, $axios })
}

export function fetchRoute ({ path, $axios }) {
  return fetch({ path: `/routes/${path}`, $axios })
}

export function fetchPageIds ({ ids, $axios }) {
  return Promise.all(ids.map(id => fetchPage({ path: id, $axios })))
}
