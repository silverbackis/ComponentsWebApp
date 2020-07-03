const https = require('https')
const fs = require('fs')

export default function ({ $axios }) {
  if (process.env.NODE_ENV === 'production') return

  const caCrt = fs.readFileSync('/certs/rootCA.pem')
  const httpsAgent = new https.Agent({ ca: caCrt, keepAlive: false })

  $axios.onRequest((config) => {
    config.httpsAgent = httpsAgent
  })
}
