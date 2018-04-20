import { Router } from 'express'
import axios from 'axios'
import cookies from './cookies'

const logging = process.env.NODE_ENV === 'development'
const router = Router()

const reqHeaders = (cookies) => {
  return {
    'X-XSRF-TOKEN': cookies['XSRF-TOKEN'] || '',
    'Cookie': 'PHPSESSID=' + cookies['PHPSESSID'] || ''
  }
}

router.post('/login', (req, res) => {
  // Only allow post requests to API
  let session = req.session
  logging && console.log(process.env.API_URL + req.body._action)
  // Post login credentials with Session ID and XSRF Header
  return axios.post(
    process.env.API_URL + req.body._action,
    {
      username: req.body.username,
      password: req.body.password
    },
    {
      headers: reqHeaders(req.cookies)
    }
  )
    .then((loginRes) => {
      logging && console.error(loginRes)

      // Set the session variable for subsequent page refreshes - cookie is http only
      session.authToken = loginRes.data.token
      // save the refresh token to the session (NEVER to the client/browser)
      // Reference: Auth0: https://auth0.com/docs/tokens/refresh-token/current#restrictions
      // "A Single Page Application (normally implementing Implicit Grant) should not under any circumstances get a refresh token. The reason for that is the sensitivity of this piece of information."
      session.refreshToken = loginRes.data.refresh_token
      cookies.setJwtCookie(res, session.authToken)
      cookies.setCookies(res, loginRes)

      res.status(200).json({ token: session.authToken })
    })
    .catch((err) => {
      logging && console.error(err)
      if (!err.response) {
        res.status(500).json({ message: err.message })
      } else {
        if (err.response.status === 401) {
          res.status(401).json(err.response.data)
        } else {
          res.status(err.response.status).json({
            message: !err.response.data ? err.message : ((err.response.data.error && err.response.data.error.exception) ? err.response.data.error.exception[0].message : err.response.data.message)
          })
        }
      }
    })
})

router.post('/refresh_token', (req, res) => {
  let session = req.session
  if (!session.refreshToken) {
    cookies.clearJwtCookie(res)
    res.status(400).json({ message: 'Invalid session - refresh_token not available' })
  }
  if (!validBaseUrl(req.body._action)) {
    cookies.clearJwtCookie(res)
    res.status(400).json({ message: 'Invalid `_action` value' })
  } else {
    return axios.post(
      req.body._action,
      {
        refresh_token: session.refreshToken
      },
      {
        headers: reqHeaders(req.cookies)
      })
      .then((refreshRes) => {
        session.authToken = refreshRes.data.token
        session.refreshToken = refreshRes.data.refresh_token
        cookies.setJwtCookie(res, session.authToken)
        res.status(200).json({ success: true, token: refreshRes.data.token })
      })
      .catch((err) => {
        cookies.clearJwtCookie(res)
        res.status(500)
        if (!err.response) {
          res.json({ success: false, message: err.message })
        } else {
          res.json({ success: false, message: 'Refresh token rejected', error: (err.response.data.error && err.response.data.error.exception) ? err.response.data.error.exception[0].message : err.response.data.message })
        }
      })
  }
})

router.post('/logout', (req, res) => {
  req.session.destroy()
  cookies.clearJwtCookie(res)
  res
    .status(200)
    .json({ success: true })
})

console.log('Express server: Auth Routes Added (server/api/auth.js)')

export default router
