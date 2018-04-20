import CookiesToHeaders from './CookiesToHeaders'
import cookies from '../server/api/cookies'
import axios from 'axios/index'
const logging = process.env.NODE_ENV === 'development'
export const RefreshToken = async (req, res, sendResult = true) => {
  let session = req.session
  let message
  if (!session.refreshToken) {
    cookies.clearJwtCookie(res)
    message = 'Invalid session - no refresh token available'
    logging && console.log(message)
    res.status(400).json({ message })
  }
  const ACTION = process.env.API_URL + '/token/refresh'
  try {
    let response = await axios.post(
      ACTION,
      {
        refresh_token: session.refreshToken
      },
      {
        headers: CookiesToHeaders(req.cookies)
      })
    const data = response.data
    session.authToken = data.token
    session.refreshToken = data.refresh_token
    cookies.setJwtCookie(res, session.authToken)
    if (!sendResult) {
      return session.authToken
    }
    res.status(200).json({ token: session.authToken })
  } catch (err) {
    logging && console.error('RefreshToken Error', err)
    cookies.clearJwtCookie(res)
    if (!sendResult) {
      throw new Error(err)
    }

    res.status(500)
    if (err.response === undefined) {
      res.json({ message: err.message || err })
      return
    }
    if (err.response.data === undefined) {
      res.json({ message: err })
      return
    }

    let isException = (err.response.data.error && err.response.data.error.exception)
    let error = isException ? err.response.data.error.exception[0].message : (err.response.data.message || 'unknown error')
    return res.json({
      message: 'Refresh token rejected',
      error
    })
  }
}

export default RefreshToken
