import setCookie from 'set-cookie-parser'

const jwtCookieName = 'TKN'
const isDev = process.env.NODE_ENV === 'development'

export default {
  setCookies (res, axiosRes) {
    let cookies = setCookie.parse(axiosRes)
    for (let cookie of cookies) {
      let isXsrf = cookie.name === 'XSRF-TOKEN'
      let domain = process.env.COOKIE_DOMAIN
      res.cookie(cookie.name, cookie.value, { path: cookie.path, secure: !isDev, httpOnly: !isXsrf, sameSite: isXsrf, domain: domain })
    }
  },
  setJwtCookie (res, token) {
    res.cookie(jwtCookieName, token, { path: '/', domain: process.env.COOKIE_DOMAIN, secure: !isDev, httpOnly: true })
  },
  clearJwtCookie (res) {
    res.clearCookie(jwtCookieName, { path: '/', domain: process.env.COOKIE_DOMAIN })
  }
}
